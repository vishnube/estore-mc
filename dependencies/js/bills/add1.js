$(document).ready(function () {

    // Initializing the from with default values.
    $('form#bls_add_form').initForm();

    $('form#bls_add_form').on('submit', function (e) {
        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

        // Value should match Tbl: bill_types.btp_type
        // "bill-type-purchase-tab" => pchs, "bill-type-sale-tab" => sls
        var billCat = (curTabID == "bill-type-purchase-tab") ? 'pchs' : 'sls';

        var billType = $('.bill_type[name=bill_type]:checked').val();
        var bls_taxable = $('#bls_add_form .taxable').prop('checked') ? 1 : 2;  // 1 => Tax, 2 => Non-Tax

        // Adding an additional input
        input.push({ name: "bls_bill_cat", value: billCat });
        input.push({ name: "bls_bill_type", value: billType }); // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
        input.push({ name: "bls_taxable", value: bls_taxable });



        // Cleaning Notice Board
        $('.blp-tbl .notice-board').html('');

        $(this).postForm(site_url("bills/save"), input, afterBillSave, function (r, form, input) {
            if (r.v_error)
                $('.blp-tbl .notice-board').html(r.v_error);
        });
    });


    // $('#bls_add_form .blp-tbl .sr-movement-row:first-child .next-input').eq(0).focus();

    $(document).on('change', '#bls_add_form .blp-tbl .blp_fk_products', function () {
        onProductChanged($(this))
    });

    $(document).on('change', '#bls_add_form #cstr_id', function () {
        onCentralStoreChanged($(this))
    });

    $(document).on('change', '#bls_add_form #prty_id', function () {
        onPartyChanged($(this))
    });

    $(document).on('change', '#bls_add_form #fmly_id', function () {
        onFamilyChanged($(this))
    });

    $(document).on('change', '#bls_add_form #prty_gst_id', function () {
        onGSTNoChanged($(this))
    });

    $(document).on('change', '#bls_add_form .taxable', function () {
        onTaxTypeChanged(true)
    });

    $(document).on('change keyup', '#bls_add_form .blp-tbl .blp_qty', function () {
        calculateBill();
    });

    $(document).on('change keyup', '#bls_add_form .blp-tbl .blp_trate', function () {
        calculateBill();
    });

    // $(document).on('change keyup', '#bls_add_form .blp-tbl .bls_cess_total', function () {
    //     calculateBill();
    // });

    $(document).on('change keyup', '#bls_add_form .blp-tbl tfoot .bls_gross_disc', function () {
        calculateBill()
    });

    $(document).on('change keyup', '#bls_add_form .blp-tbl tfoot .bls_round', function () {
        calculateBill()
    });

    $(document).on('change keyup', '#bls_add_form .blp-tbl tfoot .bls_paid', function () {
        calculateBill()
    });
});


function initProductTable() {
    $('.blp-tbl').find('.sr-movement-row').slice(1).remove();
    $('.blp-tbl').find('.sr-movement-row :input').val('');
    $('.blp-tbl').find('.sr-movement-row .blp_fk_godowns').noOption('No Godowns');
    $('.blp-tbl').find('.sr-movement-row .blp_fk_unit_groups').noOption('No Units');
    $('.blp-tbl').find('.sr-movement-row .span-tax-pcntg').html('');
    showHideTaxCols();
}

function onNewProductRow(container, lastRow, newRow) {
    newRow.find(':input:not(.blp_fk_godowns)').val('');
    newRow.find('.span-tax-pcntg').html('');
    newRow.find('.blp_fk_unit_groups').noOption('No Units');
    calculateBill();
}

function afterBillAddFormReset() {
    // Initilizing Product add Table.
    initProductTable();
    $('form#bls_add_form #fmly_id').noOption('No Families');
    $('.TTool_target').removeClass('clock').addClass('clock');
    $('.blp-tbl').find('.span-tax-pcntg').html('');
    //$('.blp-tbl').find('.blp_fk_unit_groups').noOption('No Units');
    $('.dv-gst-dt').html('');
    $('.dv-gst-dt').attr('data-stt_id', '');
    $('.dv-all-gst-dt').html('');
    $('.dv-cstr-stt-dt').html('');
    $('.dv-cstr-stt-dt').attr('data-stt_id', '');
    $('.blp-tbl .notice-board').html('');
    calculateBill();
}

function onProductRowRemoved() {
    calculateBill();
}



function afterBillSave(res, form, input) {

    var pageNo = '';
    var bls_id = getInputValue(input, 'bls_id');

    // If the action was Edit
    if (typeof bls_id != 'undefined' && bls_id > 0) {
        pageNo = $("#bls_pagination").curPage();
        loadBills(pageNo);
        activateTab('list11');
    }

    // If the action was Add
    else {
        pageNo = 0;

        // Removing all data from list table
        emptyBillsTable()
    }

    showSuccessToast('Bill saved successfully');
}


function beforeEdit_bls(bls_id) {

    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

    // Value should match Tbl: bill_types.btp_type
    // "bill-type-purchase-tab" => pchs, "bill-type-sale-tab" => sls
    var billCat = (curTabID == "bill-type-purchase-tab") ? 'pchs' : 'sls';

    var billType = $('.bill_type[name=bill_type]:checked').val();

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    //$('#emply_add_form .sr-form-tag .sr-form-title').html('EDIT EMPLOYEE');


    var input = { bls_id: bls_id, bls_bill_cat: billCat, bls_bill_type: billType };
    var url = site_url('bills/before_edit');

    $('form#bls_add_form').postForm(url, input, function (res, form) {
        form.loadFormInputs(res);


        $('.TTool_target').removeClass('clock');

        if (billCat == 'pchs') {
            $('form#bls_add_form #prty_gst_id').html(res.prty_gst_option);
            insertPartyStates(res.prty_gst_dt);

            // this should done only after insertPartyStates()
            setState($('form#bls_add_form #prty_gst_id'));

        }
        else if (billCat == 'sls') {
            $('form#bls_add_form #fmly_id').html(res.fmly_option);
        }


        insertCentralStoreStates(res.cstr_gst_dt);
        if (res.bls_taxable == 1) {
            $('#bls_add_form .taxable').prop('checked', true);
            $('#bls_add_form .bls_tax_state').val(res.bls_tax_state);
        }
        else {
            $('#bls_add_form .taxable').prop('checked', false);
        }

        // This should done only after insertPartyStates() and insertCentralStoreStates()
        onTaxTypeChanged(false);

        $('#bls_add_form #bls_date').val(res.bls_date);

        var container = $('.blp-tbl');
        var row = container.find('.sr-movement-row:first-child'); // First row of the container

        $(res.blp_data).each(function (i, blp) {
            if (i > 0)
                createNewSrInputRow(container, row, false);

            // This should do before call setProductData()
            container.find('.sr-movement-row:last-child .span-tax-pcntg').html('');
            container.find('.sr-movement-row:last-child :input').val('');

            container.find('.sr-movement-row:last-child .blp_fk_godowns').html(blp.gdn_option);

            container.find('.sr-movement-row:last-child .prd-pop').val(blp.prd_name);
            container.find('.sr-movement-row:last-child .blp_fk_products').val(blp.blp_fk_products);
            container.find('.sr-movement-row:last-child .prd_name').val(blp.prd_name);
            container.find('.sr-movement-row:last-child .blp_fk_product_batches').html(blp.pdbch_option);

            setProductData(container.find('.sr-movement-row:last-child .blp_fk_products'), blp.prd_data);
            container.find('.sr-movement-row:last-child .blp_qty').val(parseFloat(blp.blp_qty));
            container.find('.sr-movement-row:last-child .blp_trate').val(parseFloat(blp.blp_trate));
            container.find('.sr-movement-row:last-child .blp_rate').val(parseFloat(blp.blp_rate));
            container.find('.sr-movement-row:last-child .blp_amount').val(parseFloat(blp.blp_amount));

            if (res.bls_taxable == 1) {
                if (res.bls_tax_state == 1) {
                    container.find('.sr-movement-row:last-child .blp_cgst').val(parseFloat(blp.blp_cgst));
                    container.find('.sr-movement-row:last-child .blp_sgst').val(parseFloat(blp.blp_sgst));
                }
                if (res.bls_tax_state == 2) {
                    container.find('.sr-movement-row:last-child .blp_igst').val(parseFloat(blp.blp_igst));
                }
            }
            container.find('.sr-movement-row:last-child .blp_gross_amt').val(parseFloat(blp.blp_gross_amt));

        })

        //$('.blp-tbl').find('.sr-movement-row .blp_fk_godowns').noOption('No Godowns');
        //blp_fk_godowns

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        //$('#emply_add_form .sr-form-tag .sr-form-title').html('EDIT EMPLOYEE');
    }, function (res, form) {
        activateTab('list11');
        // // Hiding other tabs
        // $('#bls_main_container .tab-content a').removeClass('active');
        // $('#bls_main_container .tab-content a').removeClass('show');
        // // Activating List Tab
        // $('#bls_main_container .nav-item a[href="#list"]').click();

        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });


}



function beforeConvert(bls_id, url, input) {

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    //$('#emply_add_form .sr-form-tag .sr-form-title').html('EDIT EMPLOYEE');

    $('form#bls_add_form').postForm(url, input, function (res, form) {
        form.loadFormInputs(res);

        $('form#bls_add_form #bls_id').val('');
        $('form#bls_add_form #bls_ref_key').val(bls_id);
        $('form#bls_add_form #bls_date').val(getToday());
        $('.TTool_target').removeClass('clock').addClass('clock');

        if (input.newBillCat == 'purchase') {
            $('form#bls_add_form #prty_gst_id').html(res.prty_gst_option);
            insertPartyStates(res.prty_gst_dt);

            // this should done only after insertPartyStates()
            setState($('form#bls_add_form #prty_gst_id'));

        }
        else if (input.newBillCat == 'sale') {
            $('form#bls_add_form #fmly_id').html(res.fmly_option);
        }


        insertCentralStoreStates(res.cstr_gst_dt);
        if (res.bls_taxable == 1) {
            $('#bls_add_form .taxable').prop('checked', true);
            $('#bls_add_form .bls_tax_state').val(res.bls_tax_state);
        }
        else {
            $('#bls_add_form .taxable').prop('checked', false);
        }

        // This should done only after insertPartyStates() and insertCentralStoreStates()
        onTaxTypeChanged(false);

        var container = $('.blp-tbl');
        var row = container.find('.sr-movement-row:first-child'); // First row of the container

        $(res.blp_data).each(function (i, blp) {
            if (i > 0)
                createNewSrInputRow(container, row, false);

            // This should do before call setProductData()
            container.find('.sr-movement-row:last-child .span-tax-pcntg').html('');
            container.find('.sr-movement-row:last-child :input').val('');

            container.find('.sr-movement-row:last-child .blp_fk_godowns').html(blp.gdn_option);
            container.find('.sr-movement-row:last-child .prd-pop').val(blp.prd_name);
            container.find('.sr-movement-row:last-child .blp_fk_products').val(blp.blp_fk_products);
            container.find('.sr-movement-row:last-child .prd_name').val(blp.prd_name);
            container.find('.sr-movement-row:last-child .blp_fk_product_batches').html(blp.pdbch_option);

            setProductData(container.find('.sr-movement-row:last-child .blp_fk_products'), blp.prd_data);
            container.find('.sr-movement-row:last-child .blp_qty').val(parseFloat(blp.blp_qty));
            container.find('.sr-movement-row:last-child .blp_trate').val(parseFloat(blp.blp_trate));
            container.find('.sr-movement-row:last-child .blp_rate').val(parseFloat(blp.blp_rate));
            container.find('.sr-movement-row:last-child .blp_amount').val(parseFloat(blp.blp_amount));

            if (res.bls_taxable == 1) {
                if (container.find('.sr-movement-row:last-child .tax-col.cgst-col').hasClass('opened') && container.find('.sr-movement-row:last-child .tax-col.sgst-col').hasClass('opened')) {
                    container.find('.sr-movement-row:last-child .blp_cgst').val(parseFloat(blp.blp_cgst));
                    container.find('.sr-movement-row:last-child .blp_sgst').val(parseFloat(blp.blp_sgst));
                }
                else if (container.find('.sr-movement-row:last-child .tax-col.igst-col').hasClass('opened')) {
                    container.find('.sr-movement-row:last-child .blp_igst').val(parseFloat(blp.blp_igst));
                }
            }

            container.find('.sr-movement-row:last-child .blp_gross_amt').val(parseFloat(blp.blp_gross_amt));

        });

        calculateBill();

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        //$('#emply_add_form .sr-form-tag .sr-form-title').html('EDIT EMPLOYEE');
    }, function (res, form) {
        activateTab('list11');
        // // Hiding other tabs
        // $('#bls_main_container .tab-content a').removeClass('active');
        // $('#bls_main_container .tab-content a').removeClass('show');
        // // Activating List Tab
        // $('#bls_main_container .nav-item a[href="#list"]').click();

        var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
        Swal.fire('Oops!', msg, 'error');
        return false;
    });


}
