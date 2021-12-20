<style type="text/css">
    .list-container {
        list-style-type: none;
    }

    .list-container li {
        border-top: 1px solid #dfdfdf;
        border-bottom: 1px solid #dfdfdf;
        padding: 10px;
        white-space: nowrap;
    }

    .list-container li .icon {
        font-size: 18px;
        padding-right: 10px;
    }

    .list-container li .li-name {
        font-size: 18px;
    }

    .list-container li:hover {
        background-color: #e5e5e5;
    }
</style>

<div class="row mt-4 utsk_container">
    <div class="col-12 col-sm-3">
        <div class="sr-wraper">
            <div class="form-group">
                <label>User Group</label>
                <div class="select2-sm">
                    <select id="grp_id" tabindex="1" class="grp_option form-control form-control-sm select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                        <?= get_options($grp_option) ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <div class="m-5">
        <button class="btn btn-success" id="collapse-task">Collapse All</button>
        <button class="btn btn-danger" id="expand-task">Expand All</button>
    </div>

    <div class="mt-5" id="uutsk-main-container" style="width: 60%;"></div>

</div>



<script type="text/javascript">
    // To Work this we need some script. It has done in index.php
</script>