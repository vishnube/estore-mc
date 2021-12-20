<!-- 
Use "sr-printable-common" class for every print container. It will be hidden for media screen.
It should be placed just inside the <body>. Or in otherwords its closest parent should be <body> 

*** more deatails pleas refer css part of header.php  *** 
-->
<div class="print-holder">
    <div id="printData" class="sr-printable-common" style="width: 100%;display: none;">



        <!-- Print Content -->
        <link rel="stylesheet" href="<?= base_url('dependencies/css/print_reports.css') ?>" type="text/css" />
        <div class="print-data" style="padding-right: 10px;">
            <div class="rep-header">
                <div class="div-row clearfix" style="clear: both;">
                    <div class="div-col rep-logo"><img src="data:image/png;base64, <?= $logo_xs_base64 ?>"></div>
                    <div class="div-col rep-canvas">
                        <svg class="bix" height="126" width="115">
                            <rect style="fill:#000;stroke-width:1;stroke:#000" height="3" x="31" y="6" width="76"></rect>
                            <rect style="fill:#000;stroke-width:1;stroke:#000" x="60" width="35" y="10" height="105"></rect>
                            <rect width="7" style="fill:red;stroke-width:1;stroke:red" x="99" y="13" height="102"></rect>
                        </svg>
                    </div>
                    <div class="div-col">
                        <ul class="tpoy">
                            <li class="title1" style="display: block;"></li>
                            <li class="title2" style="display: block;"></li>
                            <li class="title3" style="display: block;"></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rep-border">
                <svg class="spx" height="6">
                    <line x1="0" y1="1" x2="100%" y2="1" style="stroke:black;stroke-width:6" />
                </svg>
            </div>
            <div class="dv-tbl-reports">
                <!-- Table content will be loaded here -->
            </div>
        </div>


    </div>
</div>