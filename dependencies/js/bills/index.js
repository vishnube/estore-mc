$(document).ready(function () {

    initBill()

    // On toggling between Purchas/Sale tabs.
    $('ul#bill-type-tab.nav-tabs a').on('show.bs.tab', function (e) {
        var curTabID = $(e.target).attr('id'); // Curret tab id
        onBillTabsToggled(curTabID);
    });

    // On bill type changed (Quotation, Order, bill, Return)
    $('.bill_type[name=bill_type]').on('change', function () {
        onBillTypeChanged($(this))
    });
});

// Initializing bill
function initBill() {
    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');
    onBillTabsToggled(curTabID);
}

// On toggling between Purchas/Sale tabs.
function onBillTabsToggled(curTabID) {
    resetSlots('#bls_add_form', curTabID);
    resetSlots('#bls_search_form', curTabID);

    var billType = $('[aria-labelledby="' + curTabID + '"] .bill_type[name=bill_type]').eq(0);
    billType.prop('checked', true);
    onBillTypeChanged(billType);

    // Reseting Add form
    $('form#bls_add_form').initForm();
}

function resetSlots(form, curTabID) {
    var slot_1 = $(form + ' .slot-1'); // Suppliers/Family
    var slot_2 = $(form + ' .slot-2'); // Central Store

    if (curTabID == "bill-type-purchase-tab") {
        $(form + ' .from-slot').append(slot_1);
        $(form + ' .to-slot').append(slot_2);
        $(form + ' .fmly-slot').hide();
        $(form + ' .sup-slot').show();
    }
    else if (curTabID == "bill-type-sale-tab") {
        $(form + ' .from-slot').append(slot_2);
        $(form + ' .to-slot').append(slot_1);
        $(form + ' .fmly-slot').show();
        $(form + ' .sup-slot').hide();
    }

    $(form + ' #fmly_id').val('');
    $(form + ' #prty_id').val('');
    $(form + ' #cstr_id').val('');
}

// function setRateField(tr, rate) {

//     tr = ifDef(tr, tr, $('.blp-tbl tbody tr'));
//     rate = ifDef(rate, rate, '');
//     var billType = $('.bill_type[name=bill_type]:checked').val();

//     // Rate Mode: Which rate field should be show in bill body (Product) section. 
//     // blp_rate => Rate Exclude Tax
//     // blp_trate => Rate Included Tax

//     /*WARNING:  If user settings are not loaded yet, its value will be undefined. So else part wiil be executed*/
//     var rateMode = $('[data-reftbl="' + billType + '"][data-cat="1"][data-key="RT_MODE"]').val()

//     // If Tax Excluded rate
//     if (rateMode == 1) {
//         tr.find('.blp_rate').show()
//         tr.find('.blp_rate').val(rate)
//         tr.find('.blp_trate').hide()
//         tr.find('.blp_trate').val('')
//     }

//     // If Tax Included rate
//     else {
//         tr.find('.blp_trate').show()
//         tr.find('.blp_trate').val(rate)
//         tr.find('.blp_rate').hide()
//         tr.find('.blp_rate').val('')
//     }
// }


// On bill type changed (Quotation, Order, bill, Return)
function onBillTypeChanged(obj) {

    // initializing Add form.
    // It is compulsory becouse, in edit action user may have a chance to change bill type.
    // To change the bill type user need to click on "Convert Buttons"
    $('form#bls_add_form').initForm();

    //setRateField()

    // Changing Title of Configuration Tab Content
    $('.bls-conf-tab .conf-title').html(obj.attr('data-btp') + ' CONFIGURATIONS');

    if (tasks['tsk_' + obj.val() + '_pdf'])
        $('.bls-export-container .export-PDF').removeAttr('disabled');
    else {
        $('.bls-export-container .export-PDF').attr('disabled', 'disabled');
    }

    if (tasks['tsk_' + obj.val() + '_excel'])
        $('.bls-export-container .export-EXCEL').removeAttr('disabled');
    else
        $('.bls-export-container .export-EXCEL').attr('disabled', 'disabled');

    if (tasks['tsk_' + obj.val() + '_print'])
        $('.bls-export-container .export-PRINT').removeAttr('disabled');
    else
        $('.bls-export-container .export-PRINT').attr('disabled', 'disabled');

    if (tasks['tsk_' + obj.val() + '_conf'])
        $('.bls-conf-tab').show();
    else {
        $('.bls-conf-tab').hide();
        activateTab('list');
    }



    // Making Configs/Bill Batch table empty.
    emptyBillBatchTable();

    // Making list tab table empty.
    $('form#bls_search_form').initForm();

    // Loading table data if 'List Tab' is opened
    if ($('#bls-list-tab').hasClass('active'))
        loadBills(0);
    else
        emptyBillsTable();
}



$(document).on('click', '.convertor', function () {
    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');
    var oldBillCat = (curTabID == "bill-type-purchase-tab") ? 'purchase' : 'sale';
    var oldBillType = $('.bill_type[name=bill_type]:checked').val();

    var newBillCat = $(this).attr('data-tab');
    var newBillType = $(this).attr('data-bill_type'); //convertTo
    activateTab('bill-type-' + newBillCat); // Activating main tabs. Purchase|Sale
    $('.bill_type[name=bill_type][value=' + newBillType + ']').prop('checked', true);

    // If no task assigned
    if (!tasks['tsk_' + newBillType + '_add']) {
        Swal.fire('Oops!', 'No task found', 'error');
        return;
    }

    activateTab('add11'); // Activating Add tab
    scrollTo($("#bls_main_container"));

    var bls_id = $(this).attr('data-bls_ref_key');

    var input = {
        bls_id: bls_id,
        oldBillCat: oldBillCat,
        oldBillType: oldBillType,
        newBillCat: newBillCat,
        newBillType: newBillType
    };
    var url = site_url('bills/before_convert');
    beforeConvert(bls_id, url, input);
});



$(document).on('click', '.dv-tbl-port .edit_bls', function () {

    var billType = $('.bill_type[name=bill_type]:checked').val();

    // If no task assigned
    if (!tasks['tsk_' + billType + '_edit']) {
        Swal.fire('Oops!', 'No task found', 'error');
        return;
    }

    activateTab('add11');
    scrollTo($("#bls_main_container"));

    var $bls_id = $(this).closest('.bls-tr').find('.bls_id').val();
    beforeEdit_bls($bls_id);
});

$(document).on('click', '.dv-tbl-port .delete_bls', function () {

    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

    // Value should match Tbl: bill_types.btp_type
    // "bill-type-purchase-tab" => pchs, "bill-type-sale-tab" => sls
    var billCat = (curTabID == "bill-type-purchase-tab") ? 'pchs' : 'sls';

    var billType = $('.bill_type[name=bill_type]:checked').val();

    // If no task assigned
    if (!tasks['tsk_' + billType + '_deactivate']) {
        Swal.fire('Oops!', 'No task found', 'error');
        return;
    }

    var $bls_id = $(this).closest('.bls-tr').find('.bls_id').val();
    var $bls_name = $(this).closest('.bls-tr').find('.bls_name').val();
    var url = site_url("bills/delete");

    var input = { bls_id: $bls_id, bls_bill_cat: billCat, bls_bill_type: billType };

    Swal.fire({
        title: 'Are you sure?',
        html: "This will delete <b>Bill No:" + $bls_name + '</b>',
        icon: 'warning',
        iconHtml: '<i class="fas fa-trash-alt"></i>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete It !!!'
    }).then((result) => {
        if (result.value) {
            $.post(url, input, function (r) {
                if (r.status == 1) {
                    Swal.fire(
                        'Deleted !',
                        '<b>Bill No: ' + $bls_name + '</b> has been deleted',
                        'success'
                    );
                    var pageNo = $("#bls_pagination").curPage();
                    loadBills(pageNo);
                }
                else {
                    var msg = typeof r.o_error == 'undefined' ? 'Couldn\'t delete ' : r.o_error;
                    Swal.fire('Oops!', msg, 'error');
                }
            }, 'json');
        }
    });
});


$(document).on('click', '.dv-tbl-port .cancel_bls', function () {

    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

    // Value should match Tbl: bill_types.btp_type
    // "bill-type-purchase-tab" => pchs, "bill-type-sale-tab" => sls
    var billCat = (curTabID == "bill-type-purchase-tab") ? 'pchs' : 'sls';

    var billType = $('.bill_type[name=bill_type]:checked').val();

    // If no task assigned
    if (!tasks['tsk_' + billType + '_cancel']) {
        Swal.fire('Oops!', 'No task found', 'error');
        return;
    }

    var $bls_id = $(this).closest('.bls-tr').find('.bls_id').val();
    var $bls_name = $(this).closest('.bls-tr').find('.bls_name').val();
    var url = site_url("bills/cancel");
    var input = { bls_id: $bls_id, bls_bill_cat: billCat, bls_bill_type: billType };
    var msg = {
        status1: 'cancel Bill No: ',
        status2: 'cancelled',
        status3: 'Cancelled',
        icon: 'warning',
        iconHtml: '<i class="fad fa-align-slash"></i>'
    };

    changeStatus(url, input, $bls_name, 3, function () {
        var pageNo = $("#bls_pagination").curPage();
        loadBills(pageNo);
    }, msg);
});