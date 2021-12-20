$(document).ready(function () {

    $(document).on('click', '#add_bill_modal .bill-add-prd', function (e) {
        var key = e.keyCode || e.which;

        // If TAB key pressed
        if (key == 9) {
            return;
        }
        //  [Alt + X]
        // [Alt + X] is used to delete row in traversing table.
        if (e.altKey && key === 88) {
            return;
        }

        e.preventDefault();

        if (!$('#add_bill_modal #cstr_id').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a Central Store!'
            });
            return;
        }

        if (!$('#add_bill_modal .bls_to_fk_members').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a Family!'
            });
            return;
        }

        $('#product_window').modal('show');
        $('#product_window #pw_prd_name').focus();

        initProductWindow();

        $('#product_window #pw_prd_name').val('');
        $('#product_window').on('shown.bs.modal', function (e) {
            $('#product_window #pw_prd_name').focus();
            listProducts();
        });
    });


    $('#product_window .apply').on('click', function () {
        $('#product_window .apply').blur();
        applyProductData();
    });

    $(document).on('click', '#product_window #prod-list-table tbody tr', function () {
        $('#product_window #prod-list-table tbody tr').removeClass('active');
        $(this).addClass('active');
        setSelectedProduct();
    });

    $(document).on('keyup', '#product_window #pw_qty', function (e) {
        var key = e.keyCode || e.which;
        if (key == ENTER) {
            if (!$('#product_window #pw_prd_id').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a product!'
                });
                return;
            }
            else if (!$(this).val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please give the Quantity'
                });
                return;
            }
            else
                $('#product_window #pw_ugp_id').focus();
        }

        setPriceGroup();
    });

    $(document).on('keyup', '#product_window #pw_ugp_id', function (e) {
        var key = e.keyCode || e.which;
        if (key == ENTER && $(this).val()) {
            if (!$('#product_window #pw_prd_id').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a product!'
                });
                return;
            }
            else if (!$('#product_window #pw_qty').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please give the Quantity'
                });
                return;
            }
            moveToBatchSection()
        }

        setPriceGroup();
    });

    $(document).on('change', '#product_window #pw_ugp_id, #product_window #pw_qty', function (e) {
        setPriceGroup();
    });


    $(document).on('keyup', '#product_window #pw_prd_name', function (e) {

        var key = e.keyCode || e.which;
        var downArrow = 40;
        var upArrow = 38;
        var tr = '';

        unsetSelectedProduct();

        if (key != ENTER) {
            $('#product_window #select-product').collapse('show');
        }

        // If DOWN ARROW key pressed
        if (key == downArrow) {

            e.preventDefault();

            if ($('#product_window #prod-list-table tbody tr.active').length) {
                if ($('#product_window #prod-list-table tbody tr.active').nextAll('tr:visible').length) {
                    tr = $('#product_window #prod-list-table tbody tr.active').nextAll('tr:visible').eq(0);
                }
                else {
                    tr = $('#product_window #prod-list-table tbody tr.active');
                }
                $('#product_window #prod-list-table tbody tr').removeClass('active');
            }
            else {
                tr = $('#product_window #prod-list-table tbody tr').filter(':visible').first();
            }

            // scrollIntoView is not jquery, but pure javascript function. so converting jquery object to javascript element by using [0]
            if (typeof tr[0] != 'undefined') {
                tr.addClass('active');
                tr[0].scrollIntoView(false);
            }
        }

        // If UP ARROW key pressed
        else if (key == upArrow) {

            e.preventDefault();

            if ($('#product_window #prod-list-table tbody tr.active').length) {
                if ($('#product_window #prod-list-table tbody tr.active').prevAll('tr:visible').length)
                    tr = $('#product_window #prod-list-table tbody tr.active').prevAll('tr:visible').eq(0);
                else
                    tr = $('#product_window #prod-list-table tbody tr.active')
                $('#product_window #prod-list-table tbody tr').removeClass('active');
            }
            else {
                tr = $('#product_window #prod-list-table tbody tr').filter(':visible').first();
            }

            // scrollIntoView is not jquery, but pure javascript function. so converting jquery object to javascript element by using [0]
            if (typeof tr[0] != 'undefined') {
                tr.addClass('active');
                tr[0].scrollIntoView(false);
            }
        }
        else if (key == ENTER) {
            e.preventDefault();
            setSelectedProduct();
        }
        else {
            listProducts();
        }
    });


}); // $(document).ready()

function listProducts() {

    var obj = $('#product_window #pw_prd_name');
    var search = obj.val().replace(/  +/g, ' '); // Replaces all multiple spaces with one
    var target = '#product_window #prod-list-table tbody';

    $('#product_window #prod-list-table tbody tr').removeClass('active');
    $("#product_window .dv-prod-tbl").scrollTop(0)

    // If no searchs in the search_box, show all rows
    if (search.length < 2) {
        $(target).find("tr").show();
    }
    else {
        var searchArr = search.trim().split(" ");
        var searchCount = searchArr.length;
        $.each($(target).find("tr"), function () {
            var row = $(this);

            var foundCount = 0;

            // Converting row text to array after replacing all  multiple spaces with one.
            var rowArr = row.text().replace(/  +/g, ' ').trim().split(" ");
            $.each(searchArr, function (i, searchSTR) {
                $.each(rowArr, function (j, rowhSTR) {
                    // Search for the 'search value' by removing all spaces from the row text.
                    if (rowhSTR.trim().toLowerCase().indexOf(searchSTR.trim().toLowerCase()) !== -1) {
                        foundCount++;
                        return false;
                    }
                });
            });

            if (foundCount && (foundCount == searchCount)) {
                row.show();
            }
            else
                row.hide();
        });

        $('#product_window #prod-list-table tbody tr').filter(':visible').first().addClass('active');
    }

}

function initProductWindow() {
    unsetSelectedProduct()

    $('#product_window #pw_prd_name').val('');
    $('#product_window #prod-list-table tbody').find("tr").show();
    $('#product_window #prod-list-table tbody tr').removeClass('active');
    $('#product_window #select-product').collapse('show');
    $('#product_window #tbl_pdbch tbody').html(get_no_result_row($('#product_window #tbl_pdbch'), 'No Batches Found'));
}


function moveToBatchSection() {
    $('#product_window #select-batch').collapse('show');
    activateTab('existing-batch');
    $('#product_window #tbl_pdbch tbody tr:first-child .batch-row-handler').focus();
}

function setSelectedProduct() {
    unsetSelectedProduct();
    var activeRow = $('#product_window #prod-list-table tbody tr.active');

    if (!activeRow.length) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please select a product!'
        });
        return;
    }

    var prd_id = activeRow.find('.prd_id').val();
    var prd_name = activeRow.find('.prd_name').text();

    $('#product_window #pw_prd_name').val(prd_name);
    $('#product_window #pw_prd_id').val(prd_id);
    onProductSelected();
}

function onProductSelected() {
    $('#product_window #pw_qty').focus();

    // Initializing the Product Batch form (@ bills\product_batch_add.php) with default values.
    var prd_id = $('#product_window #pw_prd_id').val();
    if (!prd_id)
        return;

    loadProductBatches();

    var UnitObj = $('#product_window #pw_ugp_id');
    UnitObj.noOption('No Units');
    UnitObj.closest('.pw-unt-col').find('.unt-cont').html('');
    UnitObj.postForm(site_url('bills/get_product_data'), { prd_id: prd_id }, function (r) {
        UnitObj.html(r.option);

        var html = ''
        $.each(r.option_data, function (us, unitSet) {
            html += '<div class="unit-set">'
            $.each(unitSet, function (ug, unitGroup) {
                html += '<div class="unit-groups">';
                html += '   <div class="ugp-row" data-ugp_id="' + unitGroup.ugp_id + '" data-unt_id="' + unitGroup.ugp_fk_units + '" data-is_basic="' + unitGroup.ugp_is_basic + '" data-basic_unt_id="' + unitGroup.ugp_fk_bunits + '" data-rel="' + unitGroup.ugp_rel + '" data-grp-no="' + unitGroup.ugp_group_no + '"></div>';
                html += '</div>';
            });
            html += '</div>';
        });
        UnitObj.closest('.pw-unt-col').find('.unt-cont').html(html);

        if (typeof r.hsn.hsn_gst != 'undefined' && r.hsn.hsn_gst > 0) {
            var halfGST = bcdiv(r.hsn.hsn_gst, 2, 2);
            $('#product_window #pw_cgst_p').val(halfGST);
            $('#product_window #pw_sgst_p').val(halfGST);
            $('#product_window #pw_igst_p').val(r.hsn.hsn_gst);
        }
    }, '', UnitObj, false);


    // $('form#pdbch_add_form').initForm();
    // $('form#pdbch_add_form #pdbch_fk_products').val(prd_id);
    // $('form#pdbch_add_form input[name=pdbch_expp]').val($('#product_window #prod-list-table tbody tr.active .prd_id').attr('data-prd_exp_p'));
    // $('form#pdbch_add_form input[name=pdbch_ecp]').val($('#product_window #prod-list-table tbody tr.active .prd_id').attr('data-prd_estr_cmsn_p'));
}

function unsetSelectedProduct() {
    $('#product_window #pw_prd_id').val('');
    // $('#product_window #pw_prd_name').val(''); // Don't do this here

    // It should be unselect Batch details
    unsetSelectedBatch()

    // Making Batch table empty    
    $("#product_window #tbl_pdbch tbody").html(get_no_result_row($("#product_window #tbl_pdbch"), 'No Batches Found'));

    // Reseting Batch Option
    $('#product_window #pw_pdbch_id').noOption("No Batches");

    $('#product_window #pw_qty').val('');

    // It will be done inside unsetSelectedBatch()
    // unSetpricegroup();

    $('#product_window #pw_gdn_id').val('');
    $('#product_window #pw_ugp_id').val('');
    $('#product_window #pw_cgst_p').val('');
    $('#product_window #pw_sgst_p').val('');
    $('#product_window #pw_igst_p').val('');
    $('#product_window #pw_pdbch_mrp').val('');
    $('#product_window #pw_pdbch_exp').val('');
    $('#product_window #pw_gdn_name').val('');
}

//Un-selecting all previously selected price group
function unSetpricegroup() {
    $('#product_window #pw_rate').val('');
    $('#product_window .pdbch-prices .price-row').removeClass('sel')
}

// Price groups are kept under batche rows, under Price Group Icon, when batches are loaded
function setPriceGroup() {

    unSetpricegroup();

    // var pw_rate = $('#product_window #pw_rate').val();
    var pdbch_id = $('#product_window #pw_pdbch_id').val();
    var pw_qty = $('#product_window #pw_qty').val();
    var pw_ugp_id = $('#product_window #pw_ugp_id').val();

    if (!pdbch_id || !pw_qty || !pw_ugp_id)
        return;

    // Current ugp_group set (Unit set contains ugp_id selected by user)
    // var curUgpCont = $('#product_window .unt-cont .unit-set [data-ugp_id=' + pw_ugp_id + ']').closest('.unit-set');
    // var ugp_group_no = $('#product_window .unt-cont .unit-set [data-ugp_id=' + pw_ugp_id + ']').attr('data-grp-no');

    var rel = parseFloat($('#product_window .unt-cont .unit-set [data-ugp_id="' + pw_ugp_id + '"]').attr('data-rel'));

    // Input Qty in basic unit
    var bInQty = (rel == 1 ? pw_qty : parseFloat(bcmul(pw_qty, rel, 5)));

    // Input unit in basic unit
    var bInUgpId;
    if ($('#product_window .unt-cont .unit-set [data-ugp_id="' + pw_ugp_id + '"]').attr('data-is_basic') == 1)
        bInUgpId = pw_ugp_id;
    else
        bInUgpId = $('#product_window .unt-cont .unit-set [data-ugp_id="' + pw_ugp_id + '"]').closest('.unit-set').find('[data-is_basic="1"]').attr('data-ugp_id')

    // Min-Quantity (pgprd_qty) of Price group closest to the given input qty
    var closestQty = '';

    // Finding matching qty in Price group. It should be lesser & closest to the given input qty
    $('#product_window .pdbch-prices#pdbch-' + pdbch_id + ' .price-row[data-ugp_id="' + bInUgpId + '"]').each(function () {
        var minQty = parseFloat($(this).attr('data-pgprd_qty'))
        if (minQty <= bInQty) {
            if (closestQty === '')
                closestQty = minQty;
            else
                closestQty = Math.max(closestQty, minQty);
        }
    });

    if (closestQty === '')
        return;

    // After we find closest qty, 
    // Suppose we have more than one price groups with their Min-Quantity (pgprd_qty) are same.
    // So we need to find the price group with lowest rate (pgprd_rate)
    var lowestRate = '';
    $('#product_window .pdbch-prices#pdbch-' + pdbch_id + ' .price-row[data-ugp_id="' + bInUgpId + '"][data-pgprd_qty = "' + closestQty + '"]').each(function () {
        var rate = parseFloat($(this).attr('data-pgprd_rate'))
        if (lowestRate === '')
            lowestRate = rate;
        else
            lowestRate = Math.min(lowestRate, rate);
    });

    //Now we got the appropreate Price Group and selecting this
    var priceGroup = $('#product_window .pdbch-prices#pdbch-' + pdbch_id + ' .price-row[data-pgprd_qty = "' + closestQty + '"][data-pgprd_rate = "' + lowestRate + '"]');
    priceGroup.addClass('sel');

    // Rate in Selected unit 
    var rate = (rel == 1 ? lowestRate : bcmul(lowestRate, rel, 5));
    $('#product_window #pw_rate').val(parseFloat(rate));
}



// Applying Product data to bill
function applyProductData() {

    $('#product_window .apply').blur();

    var prd_id = $('#product_window #pw_prd_id').val();
    var prd_name = $('#product_window #pw_prd_name').val();
    var pdbch_id = $('#product_window #pw_pdbch_id').val();
    var pdbch_name = $("#product_window #pw_pdbch_id option:selected").text();
    var pw_qty = $('#product_window #pw_qty').val();
    var pw_rate = $('#product_window #pw_rate').val();
    var pw_gdn_id = $('#product_window #pw_gdn_id').val();
    var pw_ugp_id = $('#product_window #pw_ugp_id').val();
    //var ugp_option = $('#product_window #pw_ugp_id').html();
    var pw_cgst_p = $('#product_window #pw_cgst_p').val();
    var pw_sgst_p = $('#product_window #pw_sgst_p').val();
    var pw_igst_p = $('#product_window #pw_igst_p').val();

    if (!prd_id || !pdbch_id || !pw_rate || !pw_gdn_id || !pw_ugp_id || !pw_qty) {
        var keys = [
            { val: prd_id, text: 'Product' },
            { val: pdbch_id, text: 'Batch' },
            { val: pw_rate, text: 'Rate' },
            { val: pw_gdn_id, text: 'Godown' },
            { val: pw_ugp_id, text: 'Unit' },
            { val: pw_qty, text: 'Quantity' }
        ];

        $.each(keys, function (i, row) {
            if (!row.val)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... Not Found.....',
                    text: row.text + ' not found'
                })
        })

        return;
    }

    var pdbch_exp = $('#product_window #pw_pdbch_exp').val();
    var pdbch_mrp = $('#product_window #pw_pdbch_mrp').val();
    var unt_name = $("#product_window #pw_ugp_id option:selected").text();
    var gdn_name = $('#product_window #pw_gdn_name').val();

    var billTbl = '#add_bill_modal #tbl-bill-items';
    $(billTbl + ' tbody .no-result-row').remove();
    var sno = $(billTbl + ' tbody tr').length;


    // New Row
    var tr = createBillRow('', sno, prd_name, pdbch_name, pdbch_exp, pdbch_mrp, pw_gdn_id, prd_id, pdbch_id, pw_cgst_p, '', pw_sgst_p, '', pw_igst_p, '', pw_qty, unt_name, pw_ugp_id, gdn_name, pw_rate, '', '', '', 'new-item');

    // Hiding Product Window    
    $('#product_window').modal('hide');

    initProductWindow();

    $(billTbl + ' tbody').append(tr);

    calculateBill($('#add_bill_modal #tbl-bill-items tbody tr'), $('#add_bill_modal #bill_add_form'));
    calculateBill($('#add_bill_modal #tbl-odr-items tbody tr:not(.item-moved)'), $('#add_bill_modal #order_add_form'));
    check_stock();
    setBillTots();
}

