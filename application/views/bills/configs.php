<div class="row p-0 p-md-2 p-lg-4">

    <div class="col-12 mb-5">
        <h1 class="conf-title text-center text-primary mv-5 p3">PURCHASE BILL CONFIGURATIONS</h1>
    </div>

    <div class="col-12  col-lg-6">
        <?php
        $this->load->view('bill_batches/index');
        $this->load->view('bill_batches/add');
        ?>
    </div>


    <div class="col-12">
        <?php //$this->load->view('unit_groups/index') 
        ?>
        <?php //$this->load->view('unit_groups/edit') 
        ?>
    </div>


    <div class="col-12">
        <?php //$this->load->view('hsn_details/index') 
        ?>
    </div>

</div>