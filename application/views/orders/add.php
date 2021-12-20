<div class="modal fade modal-fullscreen-sm" id="add_bill_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-srlight">
                <div class="modal-title col col-8">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 text-center">
                            <i class="fas fa-user" style="color:#ffc107;"></i> User ????
                            <i class="fas fa-pencil-alt cursor-pointer" title="Change"></i>
                        </div>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row bg-srlight">
                    <div class="col-12">
                        <h3 class="text-center text-danger">CENTRAL STORE: <span class="odrcstr data-html"></span></h3>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="m-2 py-2 px-2 px-md-3">
                            <div type="button" class="btn-block btn btn-info btn-flat">ODR NO: <span class="odrno data-html"></span></div>
                        </div>
                        <div class="m-2 py-2 px-2 px-md-3">
                            <div type="button" class="btn-block btn bg-fuchsia btn-flat">DATE: <span class="odrdt data-html"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="m-2 py-2 px-2 px-md-3">
                            <div type="button" class="btn-block btn btn-success btn-flat">ESTORE: <span class="odrestr data-html"></span></div>
                        </div>
                        <div class="m-2 py-2 px-2 px-md-3">
                            <div type="button" class="btn-block btn btn-danger btn-flat">STATUS: <span class="odrst data-html"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="m-2 py-2 px-2 px-md-3">
                            <div type="button" class="btn-block btn btn-primary btn-flat">AREA: <span class="odrare data-html"></span></div>
                        </div>
                        <div class="m-2 py-2 px-2 px-md-3">
                            <div type="button" class="btn-block btn btn-warning btn-flat">FAMILY: <span class="odrfmly data-html"></span></div>
                        </div>
                    </div>
                </div>

                <!-- Common for Order and Bill -->
                <input type="hidden" name="bls_taxable" class="bls_taxable data-input" value=""> <!--  1 => Tax, 2 => Non-Tax -->
                <input type="hidden" name="bls_tax_state" class="bls_tax_state data-input" value="">
                <input type="hidden" name="cstr_id" id="cstr_id" class="data-input" value="">
                <input type="hidden" name="bls_from_fk_gstnumbers" class="bls_from_fk_gstnumbers data-input" value="">
                <input type="hidden" name="fmly_id" id="fmly_id" class="bls_to_fk_members data-input" value="">
                <input type="hidden" name="bls_to_fk_gstnumbers" class="bls_to_fk_gstnumbers data-input" value="">
                <input type="hidden" name="bls_ref_key" class="bls_ref_key data-input" value="">


                <div class="row">
                    <div class="col-md-12 col-lg-4 p-2 order-section">
                        <form class="sr-form" role="form" id="order_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                            <!-- For Order only -->
                            <div class="input-head">
                                <input type="hidden" name="bls_amt_total" class="bls_amt_total data-input" value="">
                                <input type="hidden" name="bls_cgst_total" class="bls_cgst_total data-input" value="">
                                <input type="hidden" name="bls_sgst_total" class="bls_sgst_total data-input" value="">
                                <input type="hidden" name="bls_igst_total" class="bls_igst_total data-input" value="">
                                <input type="hidden" name="bls_cess_total" class="bls_cess_total data-input" value="">
                                <input type="hidden" name="bls_gross_total" class="bls_gross_total data-input" value="">
                                <input type="hidden" name="bls_gross_disc" class="bls_gross_disc data-input" value="">
                                <input type="hidden" name="bls_round" class="bls_round data-input" value="">
                                <input type="hidden" name="bls_net_amount" class="bls_net_amount data-input" value="">
                                <input type="hidden" name="bls_paid" class="bls_paid data-input" value="">
                                <input type="hidden" name="bls_balance" class="bls_balance data-input" value="">
                            </div>


                            <!-- Content  of orders\add_order.php will be added here -->
                            <div class="odr-html data-html"></div>
                        </form>
                    </div>

                    <div class="col-md-12 col-lg-8 p-2 bill-section">
                        <form class="sr-form" role="form" id="bill_add_form" onsubmit="$(this).find(':submit').prop('disabled', true)" data-afterFormInit="">
                            <!-- For Bill only -->
                            <input type="hidden" name="bls_amt_total" class="bls_amt_total data-input" value="">
                            <input type="hidden" name="bls_cgst_total" class="bls_cgst_total data-input" value="">
                            <input type="hidden" name="bls_sgst_total" class="bls_sgst_total data-input" value="">
                            <input type="hidden" name="bls_igst_total" class="bls_igst_total data-input" value="">
                            <input type="hidden" name="bls_cess_total" class="bls_cess_total data-input" value="">
                            <input type="hidden" name="bls_gross_total" class="bls_gross_total data-input" value="">
                            <input type="hidden" name="bls_gross_disc" class="bls_gross_disc data-input" value="">
                            <input type="hidden" name="bls_round" class="bls_round data-input" value="">
                            <input type="hidden" name="bls_net_amount" class="bls_net_amount data-input" value="">

                            <!-- The paid amount will show only in order section (won't be show in Bill section). Because it is a duplicate entry -->
                            <input type="hidden" name="bls_paid" class="bls_paid data-input" value="">
                            <input type="hidden" name="bls_balance" class="bls_balance data-input" value="">

                            <div class="bill-html"><?php $this->load->view('orders/add_bill') ?></div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>