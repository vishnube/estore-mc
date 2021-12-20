$(document).ready(function () {
    $(document).on('click', '#product_window #tbl_pdbch tr.pdbch-row', function (event) {

        // We have placed the edit button (.edit_pdbch) also on this row. So click on this should be avoid here.
        // Allowed only the clicks on <td>
        if (event.target.tagName.toLowerCase() === 'td') {
            $(this).find('.batch-row-handler').focus()
        }
    });

    $(document).on('click', '#product_window #tbl_pdbch .edit_pdbch', function (event) {
        var tr = $(this).closest('.pdbch-row');
        var pdbch_id = tr.find('.pdbch_id').val();
        //var pdbch_name = tr.find('.pdbch_name').val();

        activateTab('new-batch');
        var input = { pdbch_id: pdbch_id };

        $('form#pdbch_add_form').postForm(site_url("product_batches/before_edit"), input, function (res, form) {
            form.loadFormInputs(res);
        }, function (res, form) {
            activateTab('existing-batch');
            var msg = typeof res.o_error == 'undefined' ? 'Action failed' : res.o_error;
            Swal.fire('Oops!', msg, 'error');
            return false;
        });
    });

    $(document).on('click', '#product_window #tbl_pdbch .gdn-stock', function (e) {
        setSelectedGodown($(this))
    });

    $(document).on('keyup', '#product_window #tbl_pdbch .gdn-stock', function (e) {

        var key = e.keyCode || e.which;

        var downArrow = 40;
        var upArrow = 38;
        var leftArrow = 37;
        var rightArrow = 39;
        var nextTr = $(this).closest('tr').next();
        var prevTr = $(this).closest('tr').prev();

        if (key == leftArrow) {
            e.preventDefault();
            $(this).prev().focus();
        }

        else if (key == rightArrow) {
            e.preventDefault();
            $(this).next().focus();
        }

        else if (key == downArrow) {
            e.preventDefault();
            if (nextTr.length) {
                nextTr.find('.batch-row-handler').focus()
                nextTr[0].scrollIntoView(false);
            }
        }

        // If UP ARROW key pressed
        else if (key == upArrow) {
            e.preventDefault();
            if (prevTr.length) {
                prevTr.find('.batch-row-handler').focus()
                prevTr[0].scrollIntoView(false);
            }
        }
        else if (key == ENTER) {
            e.preventDefault();
            setSelectedGodown($(this))
        }
    });

    $(document).on('keyup', '#product_window #tbl_pdbch .batch-row-handler', function (e) {

        var key = e.keyCode || e.which;
        var downArrow = 40;
        var upArrow = 38;
        var tr = $(this).closest('tr');
        var nextTr = $(this).closest('tr').nextAll('.pdbch-row').eq(0);
        var prevTr = $(this).closest('tr').prevAll('.pdbch-row').eq(0);

        // If DOWN ARROW key pressed
        if (key == downArrow) {
            e.preventDefault();
            if (nextTr.length) {
                nextTr.find('.batch-row-handler').focus()
                nextTr[0].scrollIntoView(false);
            }
        }

        // If UP ARROW key pressed
        else if (key == upArrow) {
            e.preventDefault();
            if (prevTr.length) {
                prevTr.find('.batch-row-handler').focus()
                prevTr[0].scrollIntoView(false);
            }
        }
        else if (key == ENTER) {
            e.preventDefault();
            tr.next().find('.gdn-stock:first-child').focus();
        }
    });

});



$(document).on('focus', '#product_window .batch-row-handler', function () {
    unsetSelectedBatch();
    var tr = $(this).closest('tr');
    var tbl = tr.closest('table');
    tbl.find('tr.pdbch-row').removeClass('active');
    tr.addClass('active');
    setSelectedBatch();
});

function setSelectedBatch() {
    var tr = $('#product_window #tbl_pdbch tbody .pdbch-row.active');

    if (!tr.length)
        return;

    var pdbch_id = tr.find('.pdbch_id').val();
    var pdbch_name = tr.find('.pdbch_name').val();
    var selHTML = ' <span class="btn btn-danger btn-flat btn-sm">';
    selHTML += '        <span class="fa-stack">';
    selHTML += '            <i class="fas fa-circle fa-stack-2x text-white"></i>';
    selHTML += '            <i class="fal fa-check fa-stack-1x fa-inverse text-success"></i>';
    selHTML += '        </span>&nbsp;' + pdbch_name;
    selHTML += '    </span>';

    $('#product_window #pw_pdbch_id').val(pdbch_id);
    //$('#product_window #pw_pdbch_name').val(pdbch_name);
    $('#product_window .product-batch-card .sel-pdbch-dt').html(selHTML);
    setPriceGroup();
}

function setSelectedGodown(obj) {

    var billType = $('.bill_type[name=bill_type]:checked').val();

    // Stock checking: Only for sale bill, purchase return
    if (billType == 'sls_bls' || billType == 'pchs_rtn') {
        // allow to save if there is no enough stock. 1 => Allow, 2 => Not
        var noStock = $('[data-reftbl="' + billType + '"][data-cat="1"][data-key="NO_STK"]').val()
        var qty = obj.find('.gdn_id').attr('data-qty');
        if (!parseFloat(qty) && noStock == 2) {
            Swal.fire('Oops!', "No enough stock", 'error');
            return;
        }
    }

    $('#product_window #pw_gdn_id').val(obj.find('.gdn_id').val());
    onGodownSelected(obj);
}

// Checking selected godown stock unit and unit selected by user are same or not.
// If both are different, return false
function checkUnit(obj) {
    var pw_ugp_id = $('#product_window #pw_ugp_id').val(); // Unit selected by user
    var ugp_id = obj.find('.ugp_id').val(); // Unit of selected godown stock
    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');
    var billCat = (curTabID == "bill-type-purchase-tab") ? 'pchs' : 'sls';

    // To allow to choose a godown on "no stock" condition. 
    // This case occures for the products, they don't have made even its initial purchase.
    if (!parseFloat(ugp_id)) // billCat == 'pchs' && 
        return true;

    if (ugp_id == pw_ugp_id)
        return true;

    // Current ugp_group set (Unit set contains ugp_id selected by user)
    var curUgpCont = $('#product_window .unt-cont .unit-set .unit-groups [data-ugp_id=' + pw_ugp_id + ']').closest('.unit-set');

    // If the selected godown stock ugp_id is exist in Current ugp_group set
    if (curUgpCont.find('.ugp-row[data-ugp_id=' + ugp_id + ']').length)
        return true;

    return false;
}

function onGodownSelected(obj) {
    $(this).blur();

    obj.closest('table').find('tr.pdbch-row').removeClass('active');
    obj.closest('tr').prev().addClass('active');
    setSelectedBatch();

    // The selected godown stock should be in same unit as unit selected by user
    // It should be before setting price group
    if (!checkUnit(obj)) {
        unSetpricegroup();
        Swal.fire('Oops!', "Please change the unit", 'error');
        return;
    }

    setPriceGroup();

    // Applying Product data to bill
    applyProductData();
}

function unsetSelectedBatch() {
    $('#product_window #pw_pdbch_id').val('');
    $('#product_window #pw_gdn_id').val('');
    //$('#product_window #pw_pdbch_name').val('');
    $('#product_window .product-batch-card .sel-pdbch-dt').html('');
    unSetpricegroup();
}

function loadProductBatches() {

    unsetSelectedBatch();
    unSetpricegroup();

    var tbl = $('#product_window #tbl_pdbch');
    tbl.find("tbody").html(get_no_result_row(tbl, 'No Batches Found'));

    var prd_id = $('#product_window #pw_prd_id').val();
    var cstr_id = $('#bls_add_form #cstr_id').val();
    var bls_bill_type = $('.bill_type[name=bill_type]:checked').val()
    var fmly_id = $('#bls_add_form #fmly_id').val()
    // var qty = $('#product_window #pw_qty').val();
    // var ugp_id = $('#product_window #pw_ugp_id').val();

    if (!prd_id) // || !fmly_id || !cstr_id || !ugp_id
        return;

    var input = {
        pdbch_fk_products: prd_id,
        cstr_mbr_id: cstr_id,
        bls_date: $('#bls_add_form #bls_date').val(),
        bill_time: $('#bls_add_form #bill_time').val(),
        bls_bill_type: bls_bill_type,
        fmly_mbr_id: fmly_id
        // qty: qty
        // ugp_id: ugp_id
    }


    var url = site_url("product_batches/get_pdbchs");
    $('#product_window #tbl_pdbch_container').postForm(url, input, function (res, form, input) {

        if (!res.product_batch_data.length) {
            return true;
        }

        var str = "";

        $.each(res.product_batch_data, function (i, row) {
            str += "<tr class='pdbch-row'>";
            str += "<td>";
            str += "<input type='hidden' class='pdbch_id' value='" + row.pdbch_id + "'>"
            str += "<input type='hidden' class='pdbch_name' value='" + row.pdbch_name + "'>";
            str += "<button class='btn btn-sm batch-row-handler'>" + row.pdbch_name + "</button>";
            str += "</td>";
            str += '<td>' + parseFloat(row.pdbch_bp) + '</td>';
            str += '<td>' + parseFloat(row.pdbch_sp) + '</td>';

            // Date format documentation:   https://github.com/phstc/jquery-dateFormat
            str += "<td>" + $.format.date(row.pdbch_mfg + ' 00:00:00.546', "dd/MM/yyyy") + "</td>";
            str += "<td>" + $.format.date(row.pdbch_exp + ' 00:00:00.546', "dd/MM/yyyy") + "</td>";

            str += '<td>' + parseFloat(row.pdbch_mrp) + '</td>';

            str += '<td class="text-right">';

            if (row.pdbch_status == ACTIVE) {
                str += editBtn('Product Batch', ['.edit_pdbch']);
                //str += deleteBtn('Product batch', ['.deactivate_pdbch'], 'Deactivate');
            }
            // else if (row.pdbch_status == INACTIVE)
            //     str += activateBtn('Product batch', ['.activate_pdbch']);

            str += "</td>";
            str += "</tr>";
            str += "<tr class='gdn-row'><td colspan='7'>";
            $.each(row.gdn_stock, function (gdn_id, gdn_row) {
                $.each(gdn_row, function (ugp_id, ugp_row) {
                    if (ugp_row) {
                        //console.log(ugp_row)
                        var className = parseFloat(ugp_row.gdn_qty) ? ' btn-info' : ' btn-secondary';
                        str += '<button class="d-inline mx-2 gdn-stock btn-group btn-group-sm p-0">';
                        str += '<input type="hidden" class="gdn_id" value="' + ugp_row.gdn_id + '" data-qty="' + ugp_row.gdn_qty + '">';
                        str += '<input type="hidden" class="ugp_id" value="' + ugp_id + '" >';
                        str += '<span class="btn btn-sm btn-flat' + className + '">' + ugp_row.gdn_name + '</span>';

                        if (parseFloat(ugp_row.gdn_qty)) {
                            str += '<span class="btn btn-sm btn-success btn-flat">'
                            str += roundNum(parseFloat(ugp_row.gdn_qty), 2) + ' ' + ugp_row.unt_name
                            str += '</span>';
                            str += '<span class="btn btn-sm btn-warning btn-flat">Rs.'
                            str += roundNum(parseFloat(row.cstrstk[ugp_id]['bal_rate']), 2) + ' / ' + ugp_row.unt_name
                            str += '</span>';
                        }
                        else {
                            str += '<span class="btn btn-sm btn-danger btn-flat">No Stock</span>';
                        }
                        str += '</button>';
                    }
                });
            })
            $.each(row.cstrstk, function (ugp_id, cstr_row) {

                if (cstr_row) {
                    str += '<div class="btn-group btn-group-sm mx-2">';
                    str += '   <button type="button" class="btn btn-info btn-sm btn-flat">Total: ' + roundNum(parseFloat(cstr_row.bal_qty)) + ' ' + cstr_row.unt_name + ' </button>';
                    str += '   <button type="button" class="btn btn-warning btn-sm btn-flat">' + roundNum(parseFloat(cstr_row.bal_rate), 2) + ' / ' + cstr_row.unt_name + '</button>';
                    str += '</div>';
                }
            })

            if (res.price_groups && typeof res.price_groups[row.pdbch_id] != 'undefined') {
                var cls = res.price_groups[row.pdbch_id] != '' ? 'text-success' : 'text-danger'
                str += '<div class="pdbch-prices" id="pdbch-' + row.pdbch_id + '" style="display: inline-block;">';
                str += '   <button type="button" class="btn btn-tool ' + cls + '" title="Price Groups"><i class="' + res.price_groups_icon + '"></i></button>';
                $.each(res.price_groups[row.pdbch_id], function (index, pdbch_price) {
                    var dt1 = 'data-pgprd_id="' + pdbch_price.pgprd_id + '" ';
                    var dt2 = 'data-pgprd_date="' + pdbch_price.pgprd_date + '" ';
                    var dt3 = 'data-pgp_name="' + pdbch_price.pgp_name + '" ';
                    var dt4 = 'data-pgprd_qty="' + parseFloat(pdbch_price.pgprd_qty) + '" ';
                    var dt5 = 'data-unt_name="' + pdbch_price.unt_name + '" ';
                    var dt6 = 'data-ugp_id="' + pdbch_price.pgprd_fk_unit_groups + '" ';
                    var dt7 = 'data-ugp_group_no="' + pdbch_price.ugp_group_no + '" ';
                    var dt8 = 'data-pgprd_rate="' + parseFloat(pdbch_price.pgprd_rate) + '" ';
                    var dt9 = 'data-lev="' + pdbch_price.LEVEL + '" ';
                    var dt10 = 'data-pdbch_id="' + row.pdbch_id + '" ';
                    str += '   <div class="price-row d-none" ' + dt1 + ' ' + dt2 + ' ' + dt3 + ' ' + dt4 + ' ' + dt5 + ' ' + dt6 + ' ' + dt7 + ' ' + dt8 + ' ' + dt9 + ' ' + dt10 + ' >';
                    str += '        <div class="d-inline">' + pdbch_price.location + '</div>';
                    str += '        <div class="d-inline">' + pdbch_price.cstr_dt + '</div>';
                    str += '   </div>';
                });
                str += '</div>';
            }


            str += "</td></tr>";

        });
        tbl.find("tbody").html(str);

        $('#product_window #pw_pdbch_id').html(res.pdbch_html);

    }, '', $('#product_window #tbl_pdbch_container'))
}

