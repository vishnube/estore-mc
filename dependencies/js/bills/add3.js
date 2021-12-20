

// Loading Product Units
// function loadProductUnitsOptions(obj) {
//     var target = obj.closest('tr').find('.blp_fk_unit_groups');
//     target.noOption('No Units');
//     var prd_id = obj.val();
//     if (!prd_id)
//         return
//     loadOption('product_units/get_options', { prd_id: prd_id }, target, target, function () {
//         obj.closest('tr').find('.blp_qty').focus();
//     }, true);
// }


// Loading Product Units
function loadProductData(obj) {

    var prd_id = obj.val();
    var UnitObj = obj.closest('tr').find('.blp_fk_unit_groups')
    UnitObj.noOption('No Units');
    obj.closest('tr').find('.span-tax-pcntg').html('');
    obj.closest('tr').find('.blp_cgst_p').val('');
    obj.closest('tr').find('.blp_cgst').val('');
    obj.closest('tr').find('.blp_sgst_p').val('');
    obj.closest('tr').find('.blp_sgst').val('');
    obj.closest('tr').find('.blp_igst_p').val('');
    obj.closest('tr').find('.blp_igst').val('');
    //obj.closest('tr').find('.blp_qty').val('');
    obj.closest('tr').find('.blp_trate').val('');
    calculateBill();

    if (!prd_id)
        return

    obj.postForm(site_url('bills/get_product_data'), { prd_id: prd_id }, function (r) {
        setProductData(obj, r)
        calculateBill();
    }, '', UnitObj, false);

}

function setProductData(obj, r) {
    obj.closest('tr').find('.blp_fk_unit_groups').html(r.option);
    if (typeof r.hsn.hsn_gst != 'undefined' && r.hsn.hsn_gst > 0) {
        var halfGST = bcdiv(r.hsn.hsn_gst, 2, 2);
        obj.closest('tr').find('.cgst-col .span-tax-pcntg').html(halfGST + ' %');
        obj.closest('tr').find('.cgst-col .blp_cgst_p').val(halfGST);
        obj.closest('tr').find('.sgst-col .span-tax-pcntg').html(halfGST + ' %');
        obj.closest('tr').find('.sgst-col .blp_sgst_p').val(halfGST);
        obj.closest('tr').find('.igst-col .span-tax-pcntg').html(r.hsn.hsn_gst + ' %');
        obj.closest('tr').find('.igst-col .blp_igst_p').val(r.hsn.hsn_gst);
    }
}

function loadFamilies(obj) {
    var target = $('#bls_add_form #fmly_id');
    target.noOption('No Families');
    var cstr_id = obj.val();
    if (!cstr_id)
        return
    loadOption('bills/get_cstr_family_options', { mbr_id: cstr_id }, target, target);
}

function loadGSTDetails(obj) {
    var target = $('#bls_add_form #prty_gst_id');
    target.noOption('No GST');
    $('.dv-gst-dt').html('');
    $('.dv-gst-dt').attr('data-stt_id', '');
    $('.dv-all-gst-dt').html('');

    var mbr_id = obj.val();
    if (!mbr_id)
        return;

    $(this).postForm(site_url("bills/get_gst_of_member"), { mbr_id: mbr_id }, function (r) {
        target.html(r.option);
        insertPartyStates(r.dt);
        // Gst no will automatically selected after it loaded. So call onGSTNoChanged()
        onGSTNoChanged($('#bls_add_form #prty_gst_id'));
    }, '', target);
}


function insertPartyStates(gstDt) {
    var str = '';
    var dt = [];
    $(gstDt).each(function () {
        dt = $(this)[0];
        str += '<div class="gst-dt" data-gst_id="' + dt.gst_id + '" data-stt_id="' + dt.gst_fks_states + '" data-stt_name="' + dt.stt_name + '"></div>';
    });

    $('.dv-all-gst-dt').html(str);
}

function setState(obj) {
    $('.dv-gst-dt').html('');
    $('.dv-gst-dt').attr('data-stt_id', '');
    var gst_id = obj.val();

    if (!gst_id) return;

    $('.dv-gst-dt').html($('.dv-all-gst-dt .gst-dt[data-gst_id=' + gst_id + ']').attr('data-stt_name'));
    $('.dv-gst-dt').attr('data-stt_id', $('.dv-all-gst-dt .gst-dt[data-gst_id=' + gst_id + ']').attr('data-stt_id'));
}

function loadGodowns(obj) {
    var target = $('.blp-tbl .blp_fk_godowns');
    target.noOption('No Godowns');
    var cstr_id = obj.val();
    if (!cstr_id)
        return
    loadOption('bills/get_gdn_options', { mbr_id: cstr_id }, target, target, function () {
        var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');
        if (curTabID == "bill-type-purchase-tab")
            $('#bls_add_form .blp-tbl .sr-movement-row:first-child .next-input').eq(0).focus();
    }, true);
}


function loadCentralStoreState(obj) {
    $('#bls_add_form .dv-cstr-stt-dt').html('');
    $('#bls_add_form .dv-cstr-stt-dt').attr('data-stt_id', '');

    var mbr_id = obj.val();
    if (!mbr_id)
        return;

    $(this).postForm(site_url("bills/get_cstr_state_dt"), { mbr_id: mbr_id }, function (r) {
        insertCentralStoreStates(r.dt)
        var isTax = $('#bls_add_form .taxable').prop('checked');
        if (isTax)
            showHideTaxCols();
    }, '', false);
}

function insertCentralStoreStates(dt) {
    $('#bls_add_form .dv-cstr-stt-dt').html(dt.stt_name);
    $('#bls_add_form .dv-cstr-stt-dt').attr('data-stt_id', dt.stt_id);
}


function showHideTaxCols(invokeCalculate) {

    invokeCalculate = ifDef(invokeCalculate, invokeCalculate, true);

    var checked = $('#bls_add_form .taxable').prop('checked')

    if (checked) {
        $('#bls_add_form .blp-tbl .tax-col').show(); // .tax-col includes cgst, sgst, igst cols
        $('#bls_add_form .blp-tbl .tax-col.igst-col').hide(); // By Default, hiding igst

        // '.opened' is used in calculateBill() function to identify the column is visible
        // '.closed' is used in calculateBill() function to identify the column is invisible
        $('#bls_add_form .blp-tbl .tax-col').removeClass('opened').removeClass('closed');
        $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.cgst-col').addClass('opened');
        $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.sgst-col').addClass('opened');
        $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.igst-col').addClass('closed');
        $('#bls_add_form .bls_tax_state').val(1); // 1 => Intra-State (CGST|SGST), 2 => Inter-State (IGST)

        $(".notice-board").removeClass("no-tax");
        $(".notice-col").attr("colspan", 8);

        var curTabID = $('ul#bill-type-tab.nav-tabs a.active').attr('id');
        if (curTabID == "bill-type-purchase-tab") {
            var pty_stt_id = $('#bls_add_form .dv-gst-dt').attr('data-stt_id'); // State code of Party
            var cstr_stt_id = $('#bls_add_form .dv-cstr-stt-dt').attr('data-stt_id'); // State code of Central Store

            if ($('#bls_add_form #cstr_id').val() && $('#bls_add_form #prty_id').val()) {
                if (pty_stt_id && cstr_stt_id && (pty_stt_id != cstr_stt_id)) {
                    $('#bls_add_form .blp-tbl .tax-col.igst-col').show();
                    $('#bls_add_form .blp-tbl .tax-col.cgst-col').hide();
                    $('#bls_add_form .blp-tbl .tax-col.sgst-col').hide();
                    $('#bls_add_form .bls_tax_state').val(2); // 1 => Intra-State (CGST|SGST), 2 => Inter-State (IGST)

                    $(".notice-col").attr("colspan", 7);

                    $('#bls_add_form .blp-tbl .tax-col').removeClass('opened').removeClass('closed');
                    $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.cgst-col').removeClass('opened').addClass('closed');
                    $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.sgst-col').removeClass('opened').addClass('closed');
                    $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.igst-col').removeClass('closed').addClass('opened');
                }
                else {
                    $('#bls_add_form .blp-tbl .tax-col.igst-col').hide();
                    $('#bls_add_form .blp-tbl .tax-col.cgst-col').show();
                    $('#bls_add_form .blp-tbl .tax-col.sgst-col').show();
                    $('#bls_add_form .bls_tax_state').val(1); // 1 => Intra-State (CGST|SGST), 2 => Inter-State (IGST)
                    $('#bls_add_form .blp-tbl .tax-col').removeClass('opened').removeClass('closed');
                    $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.cgst-col').addClass('opened');
                    $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.sgst-col').addClass('opened');
                    $('#bls_add_form .blp-tbl .sr-movement-row .tax-col.igst-col').addClass('closed');
                }
            }
        }
    }
    else {
        $('#bls_add_form .blp-tbl .tax-col').hide();
        $(".notice-board").addClass("no-tax");
        $(".notice-col").attr("colspan", 7);
        $('#bls_add_form .bls_tax_state').val(''); // 1 => Intra-State (CGST|SGST), 2 => Inter-State (IGST)
    }

    $('#bls_add_form .blp-tbl').css('width', '100%');

    if (invokeCalculate)
        calculateBill();
}

function calRate(tr) {

    /* var billType = $('.bill_type[name=bill_type]:checked').val();
 
     // Rate Mode: Which rate field should be used in bill body (Product) section. 
     // blp_rate => Rate without Tax
     // blp_trate => Rate Included Tax
     var rateMode = $('[data-reftbl="' + billType + '"][data-cat="1"][data-key="RT_MODE"]').val()
 
     var trate = (rateMode == 1) ? tr.find('.blp_rate').val() : tr.find('.blp_trate').val();*/


    /***************************************************
    FIND OUT RATE WITHOUT TAX FROM TAXED RATE
    Suppose, Taxed Rate as        T
             Tax percentage as    P
             Rate without tax as  R

    Now we need to find 'R' from 'T'. 
    We know,
            R + (R * P/100) = T
            R(1+ [P/100]) = T
            R[(100+P)/100] = T
            +----------------------+
            |  R = (100 T)/(100+P) |
            +----------------------+
    By the above equation, Suppose taxed rate = 55 and tax percentage is 10. Then we need to get tax excluded rate R.
            R = (100*55)/(100+10)
            R = 5500/110
            R = 50

    */
    var T = tr.find('.blp_trate').val(); // Taxed rate
    var R; // Rate without tax
    var P; // Tax percentage

    var gstType = ''
    var cgst, sgst;

    if ($('#bls_add_form .taxable').prop('checked')) {
        // The class 'opened' meants that the CGST Column is visible.
        if (tr.find('.tax-col.cgst-col').hasClass('opened')) {
            cgst = tr.find('.blp_cgst_p').val();
            // cgst = cgst > 0 ? roundNum(bcmul(amt, bcdiv(cgst, 100, 5), 5), 2) : 0;
            gstType = 'state govt';
        }
        if (tr.find('.tax-col.sgst-col').hasClass('opened')) {
            sgst = tr.find('.blp_sgst_p').val();
            // sgst = sgst > 0 ? roundNum(bcmul(amt, bcdiv(sgst, 100, 5), 5), 2) : 0;
            gstType = 'state govt';
        }
        if (tr.find('.tax-col.igst-col').hasClass('opened')) {
            P = tr.find('.blp_igst_p').val();
            // P = P > 0 ? roundNum(bcmul(amt, bcdiv(P, 100, 5), 5), 2) : 0;
            gstType = 'central govt';
        }

        if (gstType == 'state govt')
            P = bcadd(cgst, sgst, 5);

        if (parseFloat(P)) {
            // Now we have, Taxed Rate 'T', Tax percentage 'P'
            // To get Rate without tax 'R' we have the equation R=(100T)/(100+P)
            var a = bcmul(100, T, 5);
            var b = bcadd(100, P, 5)
            R = bcdiv(a, b, 7)
        }
        else
            R = T;
    }
    else
        R = T;
    return roundNum(R, 7)
}

// function calculateBill() {
//     var amt = 0;
//     var cgst = 0;
//     var sgst = 0;
//     var igst = 0;
//     var gross_amt = 0;

//     var total_amt = 0;
//     var total_cgst = 0;
//     var total_sgst = 0;
//     var total_igst = 0;
//     var total_gross = 0;
//     var total_cess = 0;

//     $('#bls_add_form .blp-tbl tbody tr:not(.total-row)').each(function () {
//         var rate = calRate($(this));

//         var qty = $(this).find('.blp_qty').val();
//         //var trate = $(this).find('.blp_trate').val();

//         amt = roundNum(bcmul(qty, rate, 5), 2);
//         total_amt = bcadd(amt, total_amt, 2);
//         $(this).find('.blp_amount').val(amt);

//         if ($('#bls_add_form .taxable').prop('checked')) {

//             // The class 'opened' meants that the CGST Column is visible.
//             // This class is set by the function showHideTaxCols()
//             if ($(this).find('.tax-col.cgst-col').hasClass('opened')) {
//                 cgst = $(this).find('.blp_cgst_p').val();
//                 cgst = cgst > 0 ? roundNum(bcmul(amt, bcdiv(cgst, 100, 5), 5), 2) : 0;
//                 total_cgst = bcadd(total_cgst, cgst, 2);
//                 $(this).find('.blp_cgst').val(cgst);
//                 $(this).find('.blp_igst').val('');
//             }

//             // The class 'opened' meants that the SGST Column is visible
//             if ($(this).find('.tax-col.sgst-col').hasClass('opened')) {
//                 sgst = $(this).find('.blp_sgst_p').val();
//                 sgst = sgst > 0 ? roundNum(bcmul(amt, bcdiv(sgst, 100, 5), 5), 2) : 0;
//                 total_sgst = bcadd(total_sgst, sgst, 2);
//                 $(this).find('.blp_sgst').val(sgst);
//             }

//             // The class 'opened' means that the IGST Column is visible
//             if ($(this).find('.tax-col.igst-col').hasClass('opened')) {
//                 igst = $(this).find('.blp_igst_p').val();
//                 igst = igst > 0 ? roundNum(bcmul(amt, bcdiv(igst, 100, 5), 5), 2) : 0;
//                 total_igst = bcadd(total_igst, igst, 2);
//                 $(this).find('.blp_igst').val(igst);
//                 $(this).find('.blp_cgst').val('');
//                 $(this).find('.blp_sgst').val('');
//             }
//         }
//         else {
//             $(this).find('.blp_cgst').val('');
//             $(this).find('.blp_sgst').val('');
//             $(this).find('.blp_igst').val('');
//         }

//         gross_amt = bcadd(amt, cgst, 2);
//         gross_amt = bcadd(gross_amt, sgst, 2);
//         gross_amt = bcadd(gross_amt, igst, 2);
//         $(this).find('.blp_gross_amt').val(gross_amt);
//         total_gross = bcadd(total_gross, gross_amt, 2);
//     })

//     $('#bls_add_form .blp-tbl .total-row .bls_amt_total').val(total_amt);
//     $('#bls_add_form .blp-tbl .total-row .bls_cgst_total').val(total_cgst);
//     $('#bls_add_form .blp-tbl .total-row .bls_sgst_total').val(total_sgst);
//     $('#bls_add_form .blp-tbl .total-row .bls_igst_total').val(total_igst);
//     $('#bls_add_form .blp-tbl .total-row .bls_gross_total').val(total_gross);



//     // // ADDING CESS (Only for B2C Customers. So only in Sale)
//     // var curTabID = $('ul#bill-type-tab.nav-tabs a.active').attr('id');
//     // if (curTabID == "bill-type-sale-tab" && $('#bls_add_form #cstr_id').val()) {

//     //     // State code of Central Store
//     //     var cstr_stt_id = $('#bls_add_form .dv-cstr-stt-dt').attr('data-stt_id');

//     //     // Applicable only for Kerala
//     //     if (cstr_stt_id == 32) {
//     //         if (total_cgst || total_sgst)
//     //             total_cess = roundNum(bcmul(bcadd(total_cgst, total_sgst, 2), .01, 5), 2);
//     //         else
//     //             total_cess = roundNum(bcmul(total_igst, .01, 5), 2);
//     //     }
//     // }

//     $('#bls_add_form .blp-tbl tfoot .bls_cess_total').val(total_cess);
//     var gross_disc = $('#bls_add_form .blp-tbl tfoot .bls_gross_disc').val();
//     var netAmount = bcsub(bcadd(total_gross, total_cess, 2), gross_disc, 2)
//     var round = $('#bls_add_form .blp-tbl tfoot .bls_round').val();
//     netAmount = bcsub(netAmount, round, 2);
//     $('#bls_add_form .blp-tbl tfoot .bls_net_amount').val(netAmount);
//     var paid = $('#bls_add_form .blp-tbl tfoot .bls_paid').val();
//     var balance = bcsub(netAmount, paid, 2);
//     $('#bls_add_form .blp-tbl tfoot .bls_balance').val(balance);
// }


function calculateBill() {

    var total_amt = 0;
    var total_cgst = 0;
    var total_sgst = 0;
    var total_igst = 0;
    var total_gross = 0;

    $('#bls_add_form .blp-tbl tbody tr:not(.total-row)').each(function () {
        var qty = $(this).find('.blp_qty').val();
        var trate = $(this).find('.blp_trate').val(); // Rate with tax
        var rate = 0;
        var amt = 0; // Amount without tax
        var cgstP = ''; // CGST Percentage
        var sgstP = '';
        var igstP = '';
        var cgst = '';
        var sgst = '';
        var igst = '';
        var tax = 0; // Sum of cgst,sgst,igst.
        var taxP = 0;
        var gross_amt = 0; // Taxed amount

        // Taxed amount        
        gross_amt = roundNum(bcmul(trate, qty, 5), 2);
        $(this).find('.blp_gross_amt').val(gross_amt);
        total_gross = bcadd(total_gross, gross_amt, 2);

        // By default (When no tax)
        amt = gross_amt;
        rate = trate;

        if ($('#bls_add_form .taxable').prop('checked') && gross_amt) {

            // The class 'opened' meants that the Column (CGST/SGST/IGST) is visible.
            // This class is set by the function showHideTaxCols()
            if ($(this).find('.tax-col.cgst-col').hasClass('opened') || $(this).find('.tax-col.sgst-col').hasClass('opened')) {
                cgstP = $(this).find('.blp_cgst_p').val();
                sgstP = $(this).find('.blp_sgst_p').val();
                taxP = (cgstP || sgstP) ? bcadd(cgstP, sgstP, 3) : 0;
            }
            else if ($(this).find('.tax-col.igst-col').hasClass('opened'))
                taxP = igstP = $(this).find('.blp_igst_p').val();

            if (taxP) {
                // Now we have, Taxed Amount 'gross_amt', Tax percentage 'taxP'
                // To get Amount without tax, we have the equation:- amt = (100 * gross_amt) / (100 + taxP)
                var a = bcmul(100, gross_amt, 6);
                var b = bcadd(100, taxP, 6);
                amt = roundNum(bcdiv(a, b, 7), 2);
                tax = bcsub(gross_amt, amt, 2);
                cgst = cgstP ? roundNum(bcdiv(tax, 2, 4), 3) : ''; // Half of the tax
                sgst = cgst; // Same as cgst
                igst = igstP ? tax : '';  // Total of tax
                rate = roundNum(bcdiv(amt, qty, 5), 3);// Rate without tax
            }
        }

        $(this).find('.blp_rate').val(rate);
        $(this).find('.blp_amount').val(amt);
        $(this).find('.blp_cgst').val(cgst);
        $(this).find('.blp_sgst').val(sgst);
        $(this).find('.blp_igst').val(igst);

        total_amt = bcadd(amt, total_amt, 2);
        total_cgst = bcadd(total_cgst, cgst, 3);
        total_sgst = bcadd(total_sgst, sgst, 3);
        total_igst = bcadd(total_igst, igst, 3);
    })

    $('#bls_add_form .blp-tbl .total-row .bls_amt_total').val(total_amt);
    $('#bls_add_form .blp-tbl .total-row .bls_cgst_total').val(total_cgst);
    $('#bls_add_form .blp-tbl .total-row .bls_sgst_total').val(total_sgst);
    $('#bls_add_form .blp-tbl .total-row .bls_igst_total').val(total_igst);
    $('#bls_add_form .blp-tbl .total-row .bls_gross_total').val(total_gross);
    $('#bls_add_form .blp-tbl tfoot .bls_cess_total').val('');

    var gross_disc = $('#bls_add_form .blp-tbl tfoot .bls_gross_disc').val();
    var netAmount = bcsub(total_gross, gross_disc, 5)
    var round = $('#bls_add_form .blp-tbl tfoot .bls_round').val();
    netAmount = roundNum(bcsub(netAmount, round, 5), 2);
    $('#bls_add_form .blp-tbl tfoot .bls_net_amount').val(netAmount);

    var paid = $('#bls_add_form .blp-tbl tfoot .bls_paid').val();
    var balance = bcsub(netAmount, paid, 2);
    $('#bls_add_form .blp-tbl tfoot .bls_balance').val(balance);
}
