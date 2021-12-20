<div class="card-body sr-page-nave-container pgprd-index">
    <ul class="sr-page-nave nav nav-tabs" id="custom-content-above-tab" role="tablist">
        <?php
        if ($tsk_pgp_list) {
        ?>
            <li class="nav-item">
                <a class="nav-link active px-5" id="pgprd-list-tab" data-toggle="pill" href="#list" role="tab" aria-controls="custom-content-above-list" aria-selected="true">List</a>
            </li>
        <?php
        }

        if ($tsk_pgp_add) {
        ?>
            <li class="nav-item">
                <a class="nav-link px-5" id="pgprd-add-tab" data-toggle="pill" href="#add" role="tab" aria-controls="custom-content-above-add" aria-selected="false">Add</a>
            </li>
        <?php
        }
        ?>

    </ul>

    <div class="sr-nav-content tab-content" id="custom-content-above-tabContent">
        <?php
        if ($tsk_pgp_list) {
        ?>
            <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="custom-content-above-list-tab">
                <?php $this->load->view('price_groups_products/search') ?>
                <?php $this->load->view('price_groups_products/list'); ?>
            </div>
        <?php
        }

        if ($tsk_pgp_add) {
        ?>
            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="custom-content-above-add-tab">
                <?php
                $this->load->view('price_groups_products/add'); ?>
            </div>
        <?php
        }
        ?>

    </div>
</div>