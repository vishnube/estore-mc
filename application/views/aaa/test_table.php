<?php $this->load->view('header') ?>

<!-- <link rel="stylesheet" type="text/css" href="http://akottr.github.io/css/reset.css" />
<link rel="stylesheet" type="text/css" href="http://akottr.github.io/css/akottr.css" /> -->
<link rel="stylesheet" type="text/css" href="dependencies/plugins/table-head-drag/dragtable.css" />

<style type="text/css">
    .col-dragger {
        cursor: move;
    }

    table th {
        padding: 5px;
        border: 1px solid #000;
    }

    table th .drg-cont {
        text-align: center;
    }
</style>
<!-- Drag table head  -->



<div class="text-left m-5">
    <?php echo anchor('aaa', '<button class="btn btn-info">Back</button>'); ?>
</div>

<div class="demo ml-5">
    <h4>Drag table heads</h4>
    <h6>Source: https://www.jqueryscript.net/table/Simple-jQuery-Plugin-For-Draggable-Table-Columns-Dragtable.html#google_vignette</h6>



    <div class="code" style="display: none;">
        <h4>Date posted to server</h4>
        <div class="code-snippet">
            <pre><code class="js" style="color: green; font-size:20px;"></code></pre>
        </div>
    </div>

    <div class="demo-content" style="width: 80%;">

        <table id="mytable" class="sar-table" style="width: 100%;">
            <thead>
                <tr>
                    <th id="time-col" style="width: 110px;">
                        <div class="drg-cont"><i class="col-dragger fas fa-sort fa-rotate-90"></i></div>Time
                    </th>

                    <th id="User-col">
                        <div class="drg-cont"><i class="col-dragger fas fa-sort fa-rotate-90"></i></div>%user
                    </th>
                    <th id="Nice-col">
                        <div class="drg-cont"><i class="col-dragger fas fa-sort fa-rotate-90"></i></div>%nice
                    </th>
                    <th id="System-col">
                        <div class="drg-cont"><i class="col-dragger fas fa-sort fa-rotate-90"></i></div>%system
                    </th>
                    <th id="iowait-col">
                        <div class="drg-cont"><i class="col-dragger fas fa-sort fa-rotate-90"></i></div>%iowait
                    </th>
                    <th id="idle-col">
                        <div class="drg-cont"><i class="col-dragger fas fa-sort fa-rotate-90"></i></div>%idle
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>12:10:01 AM</td>
                    <td>Shihab</td>
                    <td>0.04</td>
                    <td>1.65</td>
                    <td>0.08</td>
                    <td>69.36</td>
                </tr>
                <tr>
                    <td>12:20:01 AM</td>
                    <td>Ali</td>
                    <td>0.00</td>
                    <td>1.64</td>
                    <td>0.08</td>
                    <td>71.74</td>

                </tr>
                <tr>
                    <td>12:30:01 AM</td>
                    <td>Baiju</td>
                    <td>0.00</td>
                    <td>1.66</td>
                    <td>0.09</td>
                    <td>68.52</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>







<?php $this->load->view('footer') ?>
<script src="dependencies/plugins/table-head-drag/jquery.dragtable.js"></script>
<script type="text/javascript">
    $(document).ready(function() {


        $("#mytable thead th").resizable({
            handles: "e",
            resize: function(event, ui) {
                //alert(ui.size.width);
            },
            stop: function(event, ui) {
                var input = {
                    colId: ui.element.attr('id'),
                    newWidth: ui.element.outerWidth()
                }
                $.post(site_url('bills/test'), input, function(r) {
                    $('.code').show();
                    $('.code code').append('<div>' + JSON.stringify(r) + '</div>');
                }, "json");
            }
        });

        $('#mytable').dragtable({
            dragHandle: '.col-dragger',
            persistState: function(table) {
                table.el.find('th').each(function(i) {
                    if (this.id != '') {
                        table.sortOrder[this.id] = i;
                    }
                });
                //table.sortOrder = JSON.stringify(table.sortOrder)
                $.post(site_url('bills/test'), table.sortOrder, function(r) {
                    console.log(r);
                    $('.code').show();
                    $('.code code').append('<div>' + JSON.stringify(r) + '</div>');
                }, "json");
            }
        });



    });
</script>