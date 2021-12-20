<div class="row p-0 p-md-2 p-lg-4">
    <div class="col-12  col-md-4">
        <?php $this->load->view('states/index') ?>
        <?php $this->load->view('states/add') ?>
    </div>
    <div class="col-12 col-md-4">
        <?php $this->load->view('districts/index') ?>
        <?php $this->load->view('districts/add') ?>
    </div>
    <div class="col-12 col-md-8">
        <?php $this->load->view('taluks/index') ?>
        <?php $this->load->view('taluks/add') ?>
    </div>
</div>