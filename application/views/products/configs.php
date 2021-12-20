<div class="row p-0 p-md-2 p-lg-4">
    <div class="col-12  col-lg-6">
        <?php $this->load->view('product_categories/index') ?>
        <?php $this->load->view('product_categories/add') ?>
    </div>


    <div class="col-12  col-lg-6">
        <?php $this->load->view('tags/index') ?>
        <?php $this->load->view('tags/add') ?>
    </div>



    <div class="col-12  col-lg-6">
        <?php $this->load->view('companies/index') ?>
        <?php $this->load->view('companies/add') ?>
    </div>



    <div class="col-12  col-lg-6">
        <?php $this->load->view('brands/index') ?>
        <?php $this->load->view('brands/add') ?>
    </div>


    <div class="col-12">
        <?php $this->load->view('unit_groups/index') ?>
        <?php
        // This view should get globaly. So put it @ products/index.php
        //$this->load->view('unit_groups/add') 
        ?>
        <?php $this->load->view('unit_groups/edit') ?>
    </div>


    <div class="col-12">
        <?php $this->load->view('hsn_details/index') ?>
        <?php
        // This view should get globaly. So put it @ products/index.php
        //$this->load->view('hsn_details/add') 
        ?>
    </div>

</div>