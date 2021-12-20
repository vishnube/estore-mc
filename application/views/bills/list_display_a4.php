<div class="dv-print-rep" style="margin: 0 auto; width: 1000px;display:none">
    <!-- 
        This file contains a table which is a copy of the #tbl_bls_a4 (below) used to export (Excel,Print) data of #tbl_bls_a4.
        So any changes making on #tbl_bls_a4 should be apply on to this table also.                 
    -->
    <?php $this->load->view('bills/list_display_a4_export') ?>
</div>










<!-- Data will be loaded here -->
<div class="card">

    <div class="card-body" id="tbl_bls_a4_footer">
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fab fa-app-store"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">QUANTITY</span>
                        <span class="info-box-number tot-val tot-qty"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">AMOUNT</span>
                        <span class="info-box-number tot-val tot-amt"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">CGST</span>
                        <span class="info-box-number tot-val tot-cgst"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="far fa-copy"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">SGST</span>
                        <span class="info-box-number tot-val tot-sgst"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-copy"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">IGST</span>
                        <span class="info-box-number tot-val tot-igst"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">GROSS AMOUNT</span>
                        <span class="info-box-number tot-val tot-grs-amt"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">KFC</span>
                        <span class="info-box-number tot-val tot-kfc"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">GROSS DISCOUNT</span>
                        <span class="info-box-number tot-val tot-grs-disc"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-circle-notch"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">ROUND OFF</span>
                        <span class="info-box-number tot-val tot-round"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-credit-card"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">NET AMOUNT</span>
                        <span class="info-box-number tot-val tot-net-amt"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-sack-dollar"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">CASH PAID</span>
                        <span class="info-box-number tot-val tot-paid"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-xl-2 col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="far fa-balance-scale"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">BALANCE</span>
                        <span class="info-box-number tot-val tot-bal"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.card-body -->

</div>

<div class="table-responsive p-0" id="tbl_bls_a4_container"></div>