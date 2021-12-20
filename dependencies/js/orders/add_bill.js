function resetSerialNo(sno) {
    sno.each(function (i) {
        $(this).html(i + 1);
    });
}

// Setting seleted poduc coun in Oe Secion
function setSelOrderCount() {
    $('#add_bill_modal .tot-prd-sel').html($('#tbl-odr-items .odr-item-check:checked').length)
}


$(document).on('click keyup', '.make_bill', function () {

    $('#add_bill_modal #bill_add_form').initForm();
    $('#add_bill_modal #order_add_form').initForm();

    // Cleaning Notice Board (Both in Bill and Order sections)
    $('#add_bill_modal .notice-board').html('');
    $('#add_bill_modal .notice-board').hide();

    // Removing previously loaded datas
    $('#add_bill_modal .bill-section #tbl-bill-items tbody').html(get_no_result_row($('#add_bill_modal .bill-section #tbl-bill-items'), "NO PRODUCTS"));
    $('#add_bill_modal .data-html').html(''); // 'data-html' is a common place where the order data will be inserted after Ajax response.
    $('#add_bill_modal .data-input').val(''); // 'data-input' is a common inputs where the order data will be inserted after Ajax response.
    clearBillTots();

    var bls_id = $(this).closest('.order-row').find('.bls_id').val();
    var cur_status = $(this).closest('.order-row').find('.cur_status').val();
    var input = { bls_id: bls_id };
    $('#add_bill_modal').modal('show');

    $('#add_bill_modal modal-body').postForm(site_url("orders/read_order"), input, function (res) {
        $('#add_bill_modal .odrcstr').html(res.odrcstr);
        $('#add_bill_modal .odrno').html(res.odrno);
        $('#add_bill_modal .odrdt').html(res.odrdt);
        $('#add_bill_modal .odrestr').html(res.odrestr);
        $('#add_bill_modal .odrst').html(res.odrst);
        $('#add_bill_modal .odrare').html(res.odrare);
        $('#add_bill_modal .odrfmly').html(res.odrfmly);

        // Common for Order and Bill 
        $('#add_bill_modal .bls_taxable').val(res.bls_taxable);
        $('#add_bill_modal .bls_tax_state').val(res.bls_tax_state);
        $('#add_bill_modal #cstr_id').val(res.bls_from_fk_members);
        $('#add_bill_modal .bls_from_fk_gstnumbers').val(res.bls_from_fk_gstnumbers);
        $('#add_bill_modal #fmly_id').val(res.bls_to_fk_members);
        $('#add_bill_modal .bls_to_fk_gstnumbers').val(res.bls_to_fk_gstnumbers);
        $('#add_bill_modal .bls_ref_key').val(res.bls_id);

        // All of the following are both in bill_add_form & order_add_form @ orders\add.php
        $('#add_bill_modal .bls_amt_total').val(res.bls_amt_total);
        $('#add_bill_modal .bls_cgst_total').val(res.bls_cgst_total);
        $('#add_bill_modal .bls_sgst_total').val(res.bls_sgst_total);
        $('#add_bill_modal .bls_igst_total').val(res.bls_igst_total);
        $('#add_bill_modal .bls_cess_total').val(res.bls_cess_total);
        $('#add_bill_modal .bls_gross_total').val(res.bls_gross_total);
        $('#add_bill_modal .bls_gross_disc').val(res.bls_gross_disc);
        $('#add_bill_modal .bls_round').val(res.bls_round);
        $('#add_bill_modal .bls_net_amount').val(res.bls_net_amount);
        $('#add_bill_modal .bls_balance').val(res.bls_balance);

        // DO IT AFTER CLARIFICATION: 
        //      The paid amount will show only in order section (won't be show in Bill section). Because it is a duplicate entry.
        //      So what about discount and round off.
        $('#add_bill_modal .bls_paid').val(res.bls_paid);

        // Order
        $('#add_bill_modal .order-section .odr-html').html(res.html);


        calculateBill($('#add_bill_modal #tbl-bill-items tbody tr:not(.no-result-row)'), $('#add_bill_modal #bill_add_form'));
        calculateBill($('#add_bill_modal #tbl-odr-items tbody tr:not(.item-moved)'), $('#add_bill_modal #order_add_form'));
    });

});

$('#add_bill_modal').on('shown.bs.modal', function () {
    $('#tbl-odr-items #odr-all-items-check').trigger('focus');
    $('#tbl-odr-items #odr-all-items-check').prop('checked', true);

    //$('#add_bill_modal .modal-body').scrollTop(0);
    $('#add_bill_modal .modal-body').animate({
        scrollTop: 0
    }, 'slow');
    onAllOdrCheck();
})

$(document).on('keydown', '#tbl-odr-items #odr-all-items-check', function (e) {
    var key = e.keyCode || e.which;
    if (key == ENTER) {
        if ($(this).prop('checked')) {
            moveToBill();
        }
    }
});

// Check and Uncheck checkboxes (Order Section)
$(document).on('change', '#tbl-odr-items #odr-all-items-check', function () {
    onAllOdrCheck();
});

function onAllOdrCheck() {
    // '.item-moved' means that the Product has been moved from Order to bill        
    $('#tbl-odr-items tr:not(.item-moved) .odr-item-check').prop('checked', $('#tbl-odr-items #odr-all-items-check').prop('checked'))
    $('#tbl-odr-items tr.item-moved .odr-item-check').prop('checked', false);
    setSelOrderCount();
}

// Check and Uncheck checkboxes (Order Section)
$(document).on('change', '#tbl-odr-items .odr-item-check', function () {
    setSelOrderCount();

    if ($('#tbl-odr-items tr:not(.item-moved) .odr-item-check:not(:checked)').length)
        $('#tbl-odr-items #odr-all-items-check').prop('checked', false)
    else
        $('#tbl-odr-items #odr-all-items-check').prop('checked', true)
});

$(document).on('click', '#add_bill_modal .mv-back', function () {
    var billTbl = '#add_bill_modal #tbl-bill-items';
    var orderTbl = '#add_bill_modal #tbl-odr-items'
    var tr = $(this).closest('tr');
    var refId = tr.attr('data-row-ref'); // To get the curresponding product row in Ordr section

    // For newly entered product, 'data-row-ref' will be null
    if (!refId) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... Can\'t back...',
            text: 'This is a new product'
        });
        return;
    }
    Swal.fire({
        title: 'Are you sure?',
        html: 'You will be able to add it again!',
        icon: "warning",
        iconHtml: '<i class="fas fa-arrow-alt-left">',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, move back!'
    }).then((result) => {
        if (result.value) {

            // Moving back
            // The class '.item-moved' means that the product in this row is moved to Bill section. 
            // So to move back, removing this class
            $(orderTbl + ' tbody tr#' + refId).removeClass('item-moved');
            $(orderTbl + ' tbody tr#' + refId + ' .odr-item-check').prop('checked', false);
            $(orderTbl + ' #odr-all-items-check').prop('checked', false);

            // The class '.item-updated' means that it is a new entry in Order with a new quantity of existing product in the row.
            // This class will be added to the Order row, when user enter a lesser qty in Billing section than in Order section
            $(orderTbl + ' tbody tr#' + refId).removeClass('item-updated');
            $(orderTbl + ' tbody tr#' + refId + ' .new_qty').hide();
            $(orderTbl + ' tbody tr#' + refId + ' .new_qty').val($(orderTbl + ' tbody tr#' + refId + ' .blp_qty').val());
            $(orderTbl + ' tbody tr#' + refId + ' .new-product').hide(); // A Badge to show it is a new entry
            $(orderTbl + ' tbody tr#' + refId + ' .qtyhtml').show();
            setSelOrderCount();

            // Deleting the current product from bill
            tr.remove();

            resetSerialNo($(billTbl + ' .sno'));
            resetSerialNo($(orderTbl + ' tr:not(.item-moved) .sno'));

            if (!$(billTbl + ' tbody tr').length) {
                $(billTbl + ' tbody').html(get_no_result_row($(billTbl), "NO PRODUCTS"));
            }
            calculateBill($('#add_bill_modal #tbl-bill-items tbody tr:not(.no-result-row)'), $('#add_bill_modal #bill_add_form'));
            calculateBill($('#add_bill_modal #tbl-odr-items tbody tr:not(.item-moved)'), $('#add_bill_modal #order_add_form'));
            setBillTots();
        }
    });
});


$(document).on('click', '#add_bill_modal .rem-itm', function () {
    var billTbl = '#add_bill_modal #tbl-bill-items';
    var orderTbl = '#add_bill_modal #tbl-odr-items'
    var tr = $(this).closest('tr');
    var refId = tr.attr('data-row-ref'); // To get the curresponding product row in Ordr section

    Swal.fire({
        title: 'Are you sure?',
        html: 'You won\'t be able to add it again!',
        icon: "warning",
        iconHtml: '<i class="fas fa-trash-alt" style="color:red;">',
        showCancelButton: true,
        confirmButtonColor: '#37ba08',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
        if (result.value) {

            if (refId)
                $(orderTbl + ' tbody tr#' + refId).addClass('item-deleted');

            // Deleting the current product from bill
            tr.remove();
            resetSerialNo($(billTbl + ' .sno'));
            if (!$(billTbl + ' tbody tr').length) {
                $(billTbl + ' tbody').html(get_no_result_row($(billTbl), "NO PRODUCTS"));
            }

            calculateBill($('#add_bill_modal #tbl-bill-items tbody tr'), $('#add_bill_modal #bill_add_form'));
            setBillTots();
        }
    });
});

// Select value on focus
$(document).on('focus', "#add_bill_modal #tbl-bill-items .new_qty", function () {
    $(this).select();
});

// On qty changed in Billing section.
$(document).on('change keyup', '#add_bill_modal #tbl-bill-items .new_qty', function () {
    var orderTbl = '#add_bill_modal #tbl-odr-items';
    var billTbl = '#add_bill_modal #tbl-bill-items';
    var tr = $(this).closest('tr');
    var refId = tr.attr('data-row-ref'); // To get the curresponding product row in Ordr section

    // For newly entered product, 'data-row-ref' will be null
    if (!refId) return;

    var realQty = parseFloat(tr.find('.blp_qty').val());
    var newQty = parseFloat($(this).val());



    if (!newQty || newQty <= 0 || isNaN(newQty)) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please enter a valid quantity'
        });
        return;
    }

    // When new qty less than real qty, Creating a new order with the remaining qty in Order section.
    if (newQty < realQty) {
        var remainQty = parseFloat(bcsub(realQty, newQty, 5));
        // The class '.item-updated' means that the qty of the product in a row in Order section has been updated in Billing section.
        // That is, there is a lesser qty entered in Billing than in Order. So the row with class 'item-updated' (in Order section) means 
        // that we need to add a new Order with the remaining qty. 
        //
        // The row with class '.item-moved' means that the prodcut in this row has been moved to billing section.
        $(orderTbl + ' tbody tr#' + refId).addClass('item-updated').removeClass('item-moved');
        $(orderTbl + ' tbody tr#' + refId + ' .new_qty').show();
        $(orderTbl + ' tbody tr#' + refId + ' .new-product').show(); // A Badge to show it is a new entry
        $(orderTbl + ' tbody tr#' + refId + ' .new_qty').val(remainQty);
        $(orderTbl + ' tbody tr#' + refId + ' .qtyhtml').hide();
    }
    else if (newQty >= realQty) {
        $(orderTbl + ' tbody tr#' + refId).removeClass('item-updated').addClass('item-moved');
        $(orderTbl + ' tbody tr#' + refId + ' .new_qty').hide();
        $(orderTbl + ' tbody tr#' + refId + ' .new-product').hide();
        $(orderTbl + ' tbody tr#' + refId + ' .new_qty').val($(orderTbl + ' tbody tr#' + refId + ' .blp_qty').val());
        $(orderTbl + ' tbody tr#' + refId + ' .qtyhtml').show();
    }
    setSelOrderCount();

    resetSerialNo($(billTbl + ' .sno'));
    resetSerialNo($(orderTbl + ' tr:not(.item-moved) .sno'));
    $(orderTbl + ' tbody tr#' + refId + ' .odr-item-check').prop('checked', false);
    $(orderTbl + ' #odr-all-items-check').prop('checked', false);

    calculateBill($('#add_bill_modal #tbl-bill-items tbody tr'), $('#add_bill_modal #bill_add_form'));
    calculateBill($('#add_bill_modal #tbl-odr-items tbody tr:not(.item-moved)'), $('#add_bill_modal #order_add_form'));
});


// Moving items from Order to Bill
$(document).on('click', '#add_bill_modal .move-to-bill', function () {
    moveToBill();
});

function moveToBill() {
    clearBillTots();
    var orderTbl = '#add_bill_modal #tbl-odr-items';
    if ($(orderTbl + ' tr.item-moved .odr-item-check:checked').length) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Some products are allready moved!!!'
        });
        return;
    }
    if ($(orderTbl + ' tr.item-updated .odr-item-check:checked').length) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'You can\'t move new entries!!!'
        });
        return;
    }
    if (!$(orderTbl + ' tr:not(.item-moved, .item-updated) .odr-item-check:checked').length) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please select products'
        });
        return;
    }

    var billTbl = '#add_bill_modal #tbl-bill-items';
    $(billTbl + ' tbody .no-result-row').remove();
    var html = '';
    var blp;
    var sno = $(billTbl + ' tbody tr').length;

    // Taking selected items in Order
    $(orderTbl + ' tr:not(.item-moved, .item-updated) .odr-item-check:checked').closest('tr').find('.blp-data').each(function () {

        blp = $(this);
        ++sno;

        // CSS: .item-moved {display: none;} @ views\orders\index.php
        // It means that the Product has been moved from Order to bill
        blp.closest('tr').addClass('item-moved');

        var rowRef = blp.closest('tr').attr('id')
        var prd_name = blp.attr('data-prd_name')
        var pdbch_name = blp.attr('data-pdbch_name')
        var pdbch_exp = blp.attr('data-pdbch_exp')
        var pdbch_mrp = blp.attr('data-pdbch_mrp')
        var blp_fk_godowns = blp.attr('data-blp_fk_godowns')
        var blp_fk_products = blp.attr('data-blp_fk_products')
        var blp_fk_product_batches = blp.attr('data-blp_fk_product_batches')
        var blp_cgst_p = blp.attr('data-blp_cgst_p')
        var blp_cgst = blp.attr('data-blp_cgst')
        var blp_sgst_p = blp.attr('data-blp_sgst_p')
        var blp_sgst = blp.attr('data-blp_sgst')
        var blp_igst_p = blp.attr('data-blp_igst_p')
        var blp_igst = blp.attr('data-blp_igst')
        var blp_qty = blp.attr('data-blp_qty')
        var unt_name = blp.attr('data-unt_name')
        var blp_fk_unit_groups = blp.attr('data-blp_fk_unit_groups')
        var gdn_name = blp.attr('data-gdn_name')
        var blp_trate = blp.attr('data-blp_trate')
        var blp_rate = blp.attr('data-blp_rate')
        var blp_amount = blp.attr('data-blp_amount')
        var blp_gross_amt = blp.attr('data-blp_gross_amt')

        html += createBillRow(rowRef, sno, prd_name, pdbch_name, pdbch_exp, pdbch_mrp, blp_fk_godowns, blp_fk_products, blp_fk_product_batches, blp_cgst_p, blp_cgst, blp_sgst_p, blp_sgst, blp_igst_p, blp_igst, blp_qty, unt_name, blp_fk_unit_groups, gdn_name, blp_trate, blp_rate, blp_amount, blp_gross_amt);
    });

    $(billTbl + ' tbody').append(html);
    resetSerialNo($(billTbl + ' .sno'));
    resetSerialNo($(orderTbl + ' tr:not(.item-moved) .sno'));
    setSelOrderCount();
    $(orderTbl + ' .odr-item-check').prop('checked', false);
    $(orderTbl + ' #odr-all-items-check').prop('checked', false);

    // It should be done only after unchecking checkboxes in Order Tabl
    setSelOrderCount();
    check_stock();

    calculateBill($('#add_bill_modal #tbl-bill-items tbody tr'), $('#add_bill_modal #bill_add_form'));
    calculateBill($('#add_bill_modal #tbl-odr-items tbody tr:not(.item-moved)'), $('#add_bill_modal #order_add_form'));

    setBillTots();

    $('#add_bill_modal .save-btn').focus();
}

function createBillRow(rowRef, sno, prd_name, pdbch_name, pdbch_exp, pdbch_mrp, blp_fk_godowns, blp_fk_products, blp_fk_product_batches, blp_cgst_p, blp_cgst, blp_sgst_p, blp_sgst, blp_igst_p, blp_igst, blp_qty, unt_name, blp_fk_unit_groups, gdn_name, blp_trate, blp_rate, blp_amount, blp_gross_amt, trClass) {

    trClass = ifDef(trClass, trClass, '')
    var html = '';
    // data-row-ref is reference number of the row in ORDER section.
    // It will be usefull when Move back the item to Order.

    // The class 'new-item' will be added on adding a new product
    html += '<tr class="' + trClass + '" data-row-ref="' + rowRef + '">';

    html += "<td>";
    html += '	<span class="sno">' + (++sno) + '</span>';
    html += '</td>';

    html += '<td>';
    html += '   <div class="">' + prd_name + '</div>';
    html += '   <div class="">';
    html += '       <span class="text-primary scrap" title="Batch">' + pdbch_name + '</span>';
    html += '       <span class="text-danger scrap" title="Expiry">' + pdbch_exp + '</span>';
    html += '       <span class="text-success scrap" title="MRP">' + parseFloat(pdbch_mrp) + '</span>';
    html += '   </div>';

    html += '   <input type="hidden" class="blp_fk_godowns" name="blp_fk_godowns[]" value="' + blp_fk_godowns + '">';
    html += '   <input type="hidden" class="prd_id" name="blp_fk_products[]" value="' + blp_fk_products + '">';
    html += '   <input type="hidden" class="blp_fk_product_batches" name="blp_fk_product_batches[]" value="' + blp_fk_product_batches + '">';
    html += '   <input type="hidden" class="blp_mrp" name="blp_mrp[]" value="' + parseFloat(pdbch_mrp) + '">';
    html += '</td>';

    var tax = '';
    var taxP = '';
    var bls_taxable = $('#add_bill_modal .bls_taxable').val();

    // 1 => Tax, 2 => Non-Tax
    if (bls_taxable == 1) {
        // 1 => Intra-State (CGST|SGST)
        if ($('#add_bill_modal .bls_tax_state').val() == 1) {
            tax = parseFloat(bcadd(blp_cgst, blp_sgst, 2));
            taxP = parseFloat(bcadd(blp_cgst_p, blp_sgst_p, 2));
        }

        // 2 => Inter-State (IGST)
        else {
            tax = parseFloat(blp_igst);
            taxP = parseFloat(blp_igst_p);
        }
    }


    html += '<td>';
    html += '   <div class="">';
    html += '       <span class="taxhtml">' + tax + '</span>';
    html += '       <input type="hidden" class="blp_cgst_p" name="blp_cgst_p[]" value="' + blp_cgst_p + '">';
    html += '       <input type="hidden" class="blp_cgst" name="blp_cgst[]" value="' + blp_cgst + '">';
    html += '       <input type="hidden" class="blp_sgst_p" name="blp_sgst_p[]" value="' + blp_sgst_p + '">';
    html += '       <input type="hidden" class="blp_sgst" name="blp_sgst[]" value="' + blp_sgst + '">';
    html += '       <input type="hidden" class="blp_igst_p" name="blp_igst_p[]" value="' + blp_igst_p + '">';
    html += '       <input type="hidden" class="blp_igst" name="blp_igst[]" value="' + blp_igst + '">';
    html += '   </div>';
    html += '   <div class="">';
    if (bls_taxable == 1)
        html += '       <span class="text-pink scrap" title="Tax = ' + taxP + ' %">' + taxP + ' %</span>';
    html += '   </div>';
    html += '</td>';

    html += '<td>';
    html += '   <div class="qty-section-1">';
    html += '       <input type="text" class="new_qty" name="new_qty[]" value="' + parseFloat(blp_qty) + '" style="width:50px;text-align: center;height: 21px;"> &nbsp;<span class="">' + unt_name + '</span>';
    html += '   </div>';

    html += '   <div class="qty-section-2">';
    html += '       <input type="hidden" class="blp_qty" name="blp_qty[]" value="' + blp_qty + '">';
    html += '       <input type="hidden" class="ugp_id" name="blp_fk_unit_groups[]" value="' + blp_fk_unit_groups + '">';
    html += '       <span class="text-primary scrap" title="Godown">' + gdn_name + '</span>';
    html += '       <div class="v_error">'; // To show validation errors
    html += '           <div class="v_error_txt"></div>';
    html += '       </div>';
    html += '   </div>';
    html += '</td>';

    html += '<td>';
    html += '   <span class="">' + parseFloat(blp_trate) + '</span>';
    html += '   <input type="hidden" class="blp_trate" name="blp_trate[]" value="' + blp_trate + '">';
    html += '   <input type="hidden" class="blp_rate" name="blp_rate[]" value="' + blp_rate + '">';
    html += '</td>';

    html += '<td>';
    html += '   <span class="grshtml">' + blp_gross_amt + '</span>';
    html += '   <input type="hidden" class="blp_amount" name="blp_amount[]" value="' + blp_amount + '">';
    html += '   <input type="hidden" class="blp_gross_amt" name="blp_gross_amt[]" value="' + blp_gross_amt + '">';
    html += '</td>';

    html += '<td class="text-right">';
    html += otherBtn('Back', ['.mv-back'], 'Move', 'fas fa-arrow-alt-left', 'color:#e9c818');
    html += deleteBtn('Product', ['.rem-itm'], 'Delete');
    html += '</td>';

    html += '</tr>';
    return html;
}

// Checking the stock position of products which are moved to Billing Section.
function check_stock() {

    // Enabling disabled elements to take its values.
    // Because serializeArray() will not include the values of disabled elements.   
    var disabled = $('form#bill_add_form').find(':input:disabled').removeAttr('disabled');

    var input = $('form#bill_add_form').serializeArray();

    // Again desabling the desabled elements
    disabled.attr('disabled', 'disabled');

    $('form#bill_add_form').postForm(site_url("orders/check_stock"), input, function () {
        setBillTots();
        $('#add_bill_modal .save-btn').focus();
    }, function (r, form, input) {
        if (r.o_error) {
            if (!o_error)
                return;
            $(document).Toasts('create', {
                class: 'bg-maroon',
                title: 'Errors',
                icon: 'fas fa-exclamation-circle fa-lg',
                subtitle: '',
                body: o_error
            })
        }
        if (r.v_error) {
            $.each(r.v_error, function (i, row) {
                $('form#bill_add_form #tbl-bill-items tbody .v_error_txt').eq(i).html(row)
                $('form#bill_add_form #tbl-bill-items tbody .v_error_txt').eq(i).css('visibility', 'visible')
            });
        }

        setBillTots();
        $('#add_bill_modal .save-btn').focus();
    }, $('form#bill_add_form'), false);
}

// The class 'no-stock' is on validation errorssd, it will show on moving order to bill if the product has no enough stock.
$(document).on('click', '#add_bill_modal .no-stock', function () {
    var td = $(this).closest('td');
    var cstr_mbr_id = $('#add_bill_modal #cstr_id').val();
    var prd_id = $(this).closest('tr').find('.prd_id').val();
    var ugp_id = $(this).closest('tr').find('.ugp_id').val();

    if (!cstr_mbr_id || !prd_id || !ugp_id) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Central Store / Product / Unit Not found'
        });
        return;
    }

    var input = {
        cstr_mbr_id: cstr_mbr_id,
        prd_id: prd_id,
        ugp_id: ugp_id
    }

    $('form#bill_add_form').postForm(site_url("stock_avg/get_all_godown_stock"), input, function (r, form, input) {

        if (!r.stock.length) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No stock available'
            });
            return;
        }

        html = '<div class="srtip-container stock-container" style="display: inline-block;">';
        html += '   <div class="srtip">';
        html += '       <div class="srtiptext srtip-arrow-right">';
        html += '           <ul class="list-group" style="width: 350px;">';
        $.each(r.stock, function (i, row) {
            html += '           <li class="list-group-item d-flex justify-content-between align-items-center"><span>' + row.gdn_name + ': ' + row.pdbch_name + ':</span> <span class="">' + parseFloat(row.stkavg_bal_qty) + ' ' + row.unt_name + '</span></li>';
        });

        html += '           </ul>';
        html += '       </div>';
        html += '    </div>';
        html += '</div>';

        td.find('.qty-section-1').prepend(html)
        $('.srtiptext').css('visibility', 'hidden')
        td.find('.srtiptext').css('visibility', 'visible')
    }, '', true, false);
})


// var tblRow = '#add_bill_modal #tbl-bill-items'; '#add_bill_modal #tbl-odr-items tr:not(.item-moved)'
// var container = '#add_bill_modal #bill_add_form'; '#add_bill_modal #order_add_form'
function calculateBill(tblRow, container) {

    var total_amt = 0;
    var total_cgst = 0;
    var total_sgst = 0;
    var total_igst = 0;
    var total_gross = 0;
    var taxable = $('#add_bill_modal .bls_taxable').val() == 1 ? true : false; // @ orders\add.php

    if (tblRow.length) {
        tblRow.each(function () {
            var qty = $(this).find('.new_qty').val();
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
            var taxState = $('#add_bill_modal .bls_tax_state').val(); // 1 => Intra-State (CGST|SGST), 2 => Inter-State (IGST) @ orders\add.php

            // Taxed amount        
            gross_amt = roundNum(bcmul(trate, qty, 5), 2);
            $(this).find('.blp_gross_amt').val(gross_amt);
            total_gross = bcadd(total_gross, gross_amt, 2);

            // By default (When no tax)
            amt = gross_amt;
            rate = trate;

            if (taxable && gross_amt) {
                // Intra-State (CGST|SGST)
                if (taxState == 1) {
                    cgstP = $(this).find('.blp_cgst_p').val();
                    sgstP = $(this).find('.blp_sgst_p').val();
                    taxP = (cgstP || sgstP) ? bcadd(cgstP, sgstP, 3) : 0;
                }

                // Inter-State (IGST)
                else if (taxState == 2)
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
            $(this).find('.taxhtml').html((tax ? tax : '')); // Only in Bill SEcion
            $(this).find('.grshtml').html(gross_amt); // Only in Bill SEcion

            // In order section
            $(this).find('.pop-odr-qty').html(qty);
            $(this).find('.pop-odr-trate').html(trate);
            $(this).find('.pop-odr-amt').html(amt);
            $(this).find('.pop-odr-cgst').html(cgst);
            $(this).find('.pop-odr-sgst').html(sgst);
            $(this).find('.pop-odr-igst').html(igst);
            $(this).find('.pop-odr-gross').html(gross_amt);

            total_amt = bcadd(amt, total_amt, 2);
            total_cgst = bcadd(total_cgst, cgst, 3);
            total_sgst = bcadd(total_sgst, sgst, 3);
            total_igst = bcadd(total_igst, igst, 3);
        })
    }

    var totTax = parseFloat(bcadd(total_igst, bcadd(total_cgst, total_sgst, 3), 3));
    var gross_disc = container.find('.bls_gross_disc').val();
    var netAmount = bcsub(total_gross, gross_disc, 5)
    var round = container.find('.bls_round').val();
    netAmount = roundNum(bcsub(netAmount, round, 5), 2);

    var paid = container.find('.bls_paid').val();
    var balance = bcsub(netAmount, paid, 2);

    container.find('.bls_amt_total').val(total_amt);
    container.find('.bls_cgst_total').val(total_cgst);
    container.find('.bls_sgst_total').val(total_sgst);
    container.find('.bls_igst_total').val(total_igst);
    container.find('.bls_gross_total').val(total_gross);
    container.find('.bls_net_amount').val(netAmount);
    container.find('.bls_balance').val(balance);

    container.find('.tot-val').closest('li').hide();
    container.find('.tot-val').html('');

    if (total_amt) {
        container.find('.tot-amt').html(total_amt);
        container.find('.tot-amt').closest('li').show();
    }
    if (totTax) {
        container.find('.tot-tax').html(totTax);
        container.find('.tot-tax').closest('li').show();
    }

    if (total_gross) {
        container.find('.tot-grs').html(total_gross);
        container.find('.tot-grs').closest('li').show();
    }

    if (gross_disc) {
        container.find('.tot-disc').html(gross_disc);
        container.find('.tot-disc').closest('li').show();
    }
    if (round) {
        container.find('.tot-round').html(round);
        container.find('.tot-round').closest('li').show();
    }

    if (netAmount) {
        container.find('.tot-net').html(netAmount);
        container.find('.tot-net').closest('li').show();
    }

    if (paid) {
        container.find('.tot-pd').html(paid);
        container.find('.tot-pd').closest('li').show();
    }

    if (balance) {
        container.find('.tot-bs').html(balance);
        container.find('.tot-bs').closest('li').show();
    }
}

function setBillTots() {
    clearBillTots();

    var totOdr = $('#add_bill_modal #tbl-odr-items tbody tr').length;
    var totMoved = $('#add_bill_modal #tbl-odr-items tbody tr.item-moved').length;

    // Total items moved from Order to Bill
    $('#add_bill_modal .bill-section .tot-moved').html(totMoved + '/' + totOdr);

    // Total items in bill in which no enough stock
    $('#add_bill_modal .bill-section .tot-no-stock').html($('#add_bill_modal #tbl-bill-items tbody .no-stock').length);

    // New items in bill (added without Order)
    $('#add_bill_modal .bill-section .tot-no-odr').html($('#add_bill_modal #tbl-bill-items tr.new-item').length);

    // Total product deleted.
    $('#add_bill_modal .bill-section .tot-del').html($('#add_bill_modal #tbl-odr-items tr.item-deleted').length);
}

function clearBillTots() {
    $('#add_bill_modal .bill-section .tot-moved').html('0');
    $('#add_bill_modal .bill-section .tot-no-stock').html('0');
    $('#add_bill_modal .bill-section .tot-no-odr').html('0');
    $('#add_bill_modal .bill-section .tot-del').html('0');
}


$('#add_bill_modal #bill_add_form').on('submit', function (e) {
    e.preventDefault();

    var print = $('#add_bill_modal #check-print').prop('checked') ? 'yes' : 'no';

    // Enabling disabled elements to take its values.
    // Because serializeArray() will not include the values of disabled elements.   
    var disabled = $('#add_bill_modal #bill_add_form').find(':input:disabled').removeAttr('disabled');

    // Cleaning Notice Board (Both in Bill and Order sections)
    $('#add_bill_modal .notice-board').html('');
    $('#add_bill_modal .notice-board').hide();

    var billCount = $('#add_bill_modal #tbl-bill-items tbody tr:not(.no-result-row)').length;
    if (!billCount) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... Nothing to bill...',
            text: 'Please Add Products to Bill'
        });

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        // Reseting Submit Button
        $('#add_bill_modal  :submit').prop("disabled", false);

        return false;
    }

    var orderCount = $('#add_bill_modal #tbl-odr-items tbody tr:not(.item-moved)').length;

    var inputBill = $('#add_bill_modal #bill_add_form').serializeArray();

    // Adding additional Bill datas
    inputBill.push({ name: "bls_ref_key", value: $('#add_bill_modal .bls_ref_key').val() });
    inputBill.push({ name: "bls_date", value: getToday() });
    inputBill.push({ name: "bill_time", value: getTime() });
    inputBill.push({ name: "bls_bill_cat", value: 'sls' });
    inputBill.push({ name: "bls_bill_type", value: 'sls_bls' }); // sls_odr, sls_bls, sls_qtn, ect
    inputBill.push({ name: "bls_taxable", value: $('#add_bill_modal .bls_taxable').val() });
    inputBill.push({ name: "bls_tax_state", value: $('#add_bill_modal .bls_tax_state').val() });
    inputBill.push({ name: "cstr_id", value: $('#add_bill_modal #cstr_id').val() });
    inputBill.push({ name: "bls_from_fk_gstnumbers", value: $('#add_bill_modal .bls_from_fk_gstnumbers').val() });
    inputBill.push({ name: "fmly_id", value: $('#add_bill_modal #fmly_id').val() });
    inputBill.push({ name: "bls_to_fk_gstnumbers", value: $('#add_bill_modal .bls_to_fk_gstnumbers').val() });
    inputBill.push({ name: "print_bill", value: print });

    var inputOrder = new Object();
    if (orderCount) {
        inputOrder = $('#add_bill_modal #order_add_form').find('.input-head :input, #tbl-odr-items tbody tr:not(.item-moved) :input').serializeArray();

        // Adding additional Order datas
        inputOrder.push({ name: "bls_ref_key", value: $('#add_bill_modal .bls_ref_key').val() });
        inputOrder.push({ name: "bls_date", value: getToday() });
        inputOrder.push({ name: "bill_time", value: getTime() });
        inputOrder.push({ name: "bls_bill_cat", value: 'sls' });
        inputOrder.push({ name: "bls_bill_type", value: 'sls_odr' }); // sls_odr, sls_bls, sls_qtn, ect
        inputOrder.push({ name: "bls_taxable", value: $('#add_bill_modal .bls_taxable').val() });
        inputOrder.push({ name: "bls_tax_state", value: $('#add_bill_modal .bls_tax_state').val() });
        inputOrder.push({ name: "cstr_id", value: $('#add_bill_modal #cstr_id').val() });
        inputOrder.push({ name: "bls_from_fk_gstnumbers", value: $('#add_bill_modal .bls_from_fk_gstnumbers').val() });
        inputOrder.push({ name: "fmly_id", value: $('#add_bill_modal #fmly_id').val() });
        inputOrder.push({ name: "bls_to_fk_gstnumbers", value: $('#add_bill_modal .bls_to_fk_gstnumbers').val() });
    }

    // Again desabling the desabled elements
    disabled.attr('disabled', 'disabled');

    var billValidation = false;
    var orderValidation = false;

    // Validating Bill
    $('#add_bill_modal #bill_add_form').postForm(site_url("bills/validate"), inputBill, function (r, form, input) {
        if (r.status == 1)
            billValidation = true;
    }, function (r, form, input) {
        $('#add_bill_modal .modal-body').animate({
            scrollTop: 200
        }, 'slow');

        if (r.v_error) {
            $('#add_bill_modal .bill-section .notice-board').html(r.v_error);
            $('#add_bill_modal .bill-section .notice-board').show();
        }
        if (r.o_error) {
            $(document).Toasts('create', {
                class: 'bg-maroon',
                title: 'Errors',
                icon: 'fas fa-exclamation-circle fa-lg',
                subtitle: '',
                body: r.o_error
            })
        }
    }, $('#add_bill_modal #bill_add_form'), false);

    // Validating Order
    if (orderCount) {
        $('#add_bill_modal #order_add_form').postForm(site_url("bills/validate"), inputOrder, function (r, form, input) {
            if (r.status == 1)
                orderValidation = true;
        }, function (r, form, input) {
            // $(window).scrollTop(0);
            if (r.v_error) {
                $('#add_bill_modal .order-section .notice-board').html(r.v_error);
                $('#add_bill_modal .order-section .notice-board').show()
            }
            if (r.o_error) {
                $(document).Toasts('create', {
                    class: 'bg-maroon',
                    title: 'Errors',
                    icon: 'fas fa-exclamation-circle fa-lg',
                    subtitle: '',
                    body: r.o_error
                })
            }
        }, $('#add_bill_modal #order_add_form'), false);
    }
    else
        orderValidation = true;

    var saved = false;
    function save() {
        if (billValidation && orderValidation) {
            if (!saved) {
                saved = true;

                // Saving Bill
                $('#add_bill_modal #bill_add_form').postForm(site_url("bills/save/1"), inputBill, afterBillSave);

                // Saving Order
                if (orderCount) {
                    $('#add_bill_modal #order_add_form').postForm(site_url("bills/save/1"), inputOrder, function (res, form, input) {
                        showSuccessToast('Order saved successfully');
                    });
                }

                return;
            }
        }
        else {
            setTimeout(save, 20);
        }
    }
    if (!saved)
        save();

});

function afterBillSave(res, form, input) {
    if ($('#add_bill_modal #check-print').prop('checked')) {
        alert("Print")
        //printNewWindow(res.html, 1000, false);
        $('body').append('<div id="bill-print-container" class="sr-printable-common" style="width: 100%;">' + res.print_html + '</div>')
        setTimeout(function () {
            window.print();
            $('#bill-print-container').remove();
        }, 10);
    }

    loadOrders(0);
    $('#add_bill_modal').modal('hide');
    showSuccessToast('Bill saved successfully', 5000);


    // var bls_id = getInputValue(input, 'bls_ref_key'); // Getting current processed Order no:
    // var curRow = $("#tbl_odr tbody tr#odr-row-" + bls_id); //
    // var nexRow = curRow.nextAll('.order-row').eq(0);

    // curRow.find('.odr-check').prop('checked', true)
    // curRow.addClass('active2');
    // nexRow.addClass('active');
    // nexRow.find('.handler').focus();
}
