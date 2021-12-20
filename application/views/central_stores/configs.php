<div class="row p-0 p-md-2 p-lg-4">
    <div class="col-12  col-md-6 col-xl-4">
        <?php $this->load->view('central_store_categories/index') ?>
        <?php $this->load->view('central_store_categories/add') ?>
    </div>


    <div class="col-12  col-md-6">
        <?php
        $this->load->view('godowns/index');
        $this->load->view('godowns/add');
        ?>
    </div>


    <div class="col-sm-4"></div>
</div>