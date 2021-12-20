<?php if ($post == 'ajax') {
?>
    <table id="mytable" class="sar-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sold Qty</th>
                <th>Sold Rate</th>
                <th>Sold Amt</th>
                <th>Pur Qty</th>
                <th>Pur Rate</th>
                <th>Pur Amt</th>
                <th>Profit</th>
                <th>Cls Qty</th>
                <th>Cls Rate</th>
                <th>Cls Amt</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($tbl) {
                foreach ($tbl as $row) {
                }
            }
            ?>
        </tbody>
    </table>
<?php
} else {
?>



    <?php $this->load->view('header') ?>

    <!-- <link rel="stylesheet" type="text/css" href="http://akottr.github.io/css/reset.css" />
<link rel="stylesheet" type="text/css" href="http://akottr.github.io/css/akottr.css" /> -->
    <link rel="stylesheet" type="text/css" href="dependencies/plugins/table-head-drag/dragtable.css" />

    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        table th,
        td {
            padding: 5px;
            border: 1px solid #000;
        }
    </style>



    <div class="ml-5">
        <div class="text-left m-5">
            <?php echo anchor('aaa/test', '<button class="btn btn-danger">Example for Drag/Resize Table Head</button>'); ?>
        </div>

        <div>
            <form class="sr-form" role="form" id="my_form" data-afterFormInit="">
                <div class="row">
                    <div class="col-3">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>From:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" data-default="<?= today() ?>" name="f_date" id="f_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="sr-wraper">
                            <div class="form-group">
                                <label>To:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" data-default="<?= today() ?>" name="t_date" id="t_date" class="sr-date-field-1 form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="demo ml-5">

            <div class="demo-content">


            </div>
        </div>
    </div>







    <?php $this->load->view('footer') ?>

    <script type="text/javascript">
        $(document).ready(function() {

            load_data();



        });

        function load_data() {
            var url = site_url("aaa/index");

            $.post(url, {
                post: 'ajax'
            }, function(res) {
                $('.demo-content').html(res)
            });
        }
    </script>

<?php
}
?>