$(document).ready(function () {

    $(document).on('keydown', '.blp-tbl .prd-pop', function (e) {
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

        if (!$('#bls_add_form #cstr_id').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select a Central Store!'
            });
            return;
        }

        // If Sale 
        if ($("ul#bill-type-tab.nav-tabs a.active").attr('id') == "bill-type-sale-tab") {
            if (!$('#bls_add_form #fmly_id').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a Family!'
                });
                return;
            }
        }

        $('#product_window').modal('show');
        $('#product_window #pw_prd_name').focus();

        initProductWindow();

        var val = $(this).val();

        // Setting Current row as Active
        $('.blp-tbl .sr-movement-row').removeClass('active');
        $(this).closest('.sr-movement-row').addClass('active');

        // Detaching the current product data
        // detachProduct();

        $('#product_window #pw_prd_name').val(val);
        $('#product_window').on('shown.bs.modal', function (e) {
            $('#product_window #pw_prd_name').focus();
            listProducts();
        });
    });


    $('#product_window .apply').on('click', function () {
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

    // $(document).on('keyup', '#product_window', function (e) {
    //     var key = e.keyCode || e.which;
    //     if (key == ENTER)
    //         alert("I am Prd Window")
    // });


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

    // Initializing the Product Batch form (@ bills\product_batch_add.php) with default values.
    $('form#pdbch_add_form').initForm();
    $('#product_window #tbl_pdbch tbody').html(get_no_result_row($('#product_window #tbl_pdbch'), 'No Batches Found'));
}


function moveToBatchSection() {
    $('#product_window #select-batch').collapse('show');
    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

    // If Purchase && there is no batches there
    if ((curTabID == "bill-type-purchase-tab") && $('#product_window #tbl_pdbch tr.no-result-row').length) {
        activateTab('new-batch');
        $('#pdbch_add_form .form-control').eq(0).focus();
    }
    else {
        activateTab('existing-batch');
        $('#product_window #tbl_pdbch tbody tr:first-child .batch-row-handler').focus();
    }
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


    $('form#pdbch_add_form').initForm();
    $('form#pdbch_add_form #pdbch_fk_products').val(prd_id);
    $('form#pdbch_add_form input[name=pdbch_expp]').val($('#product_window #prod-list-table tbody tr.active .prd_id').attr('data-prd_exp_p'));
    $('form#pdbch_add_form input[name=pdbch_ecp]').val($('#product_window #prod-list-table tbody tr.active .prd_id').attr('data-prd_estr_cmsn_p'));
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

    // Current Row
    var tr = $('.blp-tbl .sr-movement-row.active');

    var prd_id = $('#product_window #pw_prd_id').val();
    var prd_name = $('#product_window #pw_prd_name').val();
    var pdbch_id = $('#product_window #pw_pdbch_id').val();
    var pw_qty = $('#product_window #pw_qty').val();
    var pw_rate = $('#product_window #pw_rate').val();
    var pw_gdn_id = $('#product_window #pw_gdn_id').val();
    var pw_ugp_id = $('#product_window #pw_ugp_id').val();
    var ugp_option = $('#product_window #pw_ugp_id').html();
    var pw_cgst_p = $('#product_window #pw_cgst_p').val();
    var pw_sgst_p = $('#product_window #pw_sgst_p').val();
    var pw_igst_p = $('#product_window #pw_igst_p').val();
    //var pdbch_name = $('#product_window #pw_pdbch_name').val();

    if (!prd_id) {
        alert("Pleae select a product")
        return;
    }

    if (!tr.length) {
        alert("Product row couldn't identify");
        return;
    }

    // Hiding Product Window    
    $('#product_window').modal('hide');

    tr.find('.prd-pop').val(prd_name);

    tr.find('.blp_fk_products').val(prd_id);
    tr.find('.prd_name').val(prd_name);

    tr.find('.blp_fk_product_batches').html($('#product_window #pw_pdbch_id').html());
    tr.find('.blp_fk_product_batches').val(pdbch_id);

    tr.find('.blp_fk_unit_groups').html(ugp_option);
    tr.find('.blp_fk_unit_groups').val(pw_ugp_id);
    tr.find('.blp_qty').val(pw_qty);
    tr.find('.blp_trate').val(pw_rate);
    tr.find('.blp_fk_godowns').val(pw_gdn_id);
    tr.find('.cgst-col .span-tax-pcntg').html(pw_cgst_p + ' %');
    tr.find('.cgst-col .blp_cgst_p').val(pw_cgst_p);
    tr.find('.sgst-col .span-tax-pcntg').html(pw_sgst_p + ' %');
    tr.find('.sgst-col .blp_sgst_p').val(pw_sgst_p);
    tr.find('.igst-col .span-tax-pcntg').html(pw_igst_p + ' %');
    tr.find('.igst-col .blp_igst_p').val(pw_igst_p);

    tr.find('.rate_field:visible').focus();
    $('.blp-tbl .sr-movement-row').removeClass('active');

    calculateBill();
}


// Detach Product data from the active row
// function detachProduct() {
//     var tr = $('.blp-tbl .sr-movement-row.active');
//     tr.find('.prd-pop').val('');
//     tr.find('.blp_fk_products').val('');
//     tr.find('.blp_fk_product_batches').val('');
//     tr.find('.prd_name').val('');
//     tr.find('.pdbch_name').val('');
//     onProductChanged(tr.find('.blp_fk_products'));
// }

// function alertNoProduct() {
//     Swal.fire({
//         icon: 'error',
//         title: 'Oops...',
//         text: 'Please select a product!'
//     }).then((result) => {
//         // if (result.isConfirmed)
//         //     swal.fire("Done!", "It was succesfully deleted!", "success");
//         // else
//         //     swal.fire("Error!", "Coudn't delet!", "error");
//     });
// }