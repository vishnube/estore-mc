var stock; // Global Var

$(document).ready(function () {

    // Initializing the from with default values.
    $('form#product_load_form').initForm();

    $(document).on('change', 'form#product_load_form .pct_parent_selector', function (e) {
        create_pct_parent_radios($(this), 'rad_product_load_form', ''); // @ product_categories/add.js
    });

    $(document).on('click', 'form#product_load_form .reset-pctopt', function (e) {
        reset_pct($(this).closest('form')); // @ product_categories/add.js
    });

    // Detect pagination click
    $("#prd_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        loadProducts(pageno);
    });

    // #product-details @ price_groups_products\product_details.php
    // .pgprd_fk_unit_groups will be created on ajax call
    $(document).on('change', '#product-details #tbl_prd_dt .pgprd_fk_unit_groups', function (event) {
        getAVGRate($(this));
    });

    $(document).on('click', '.pgprd-index .prd-unselected #tbl_prd tr', function (event) {

        // We have placed the edit button (.btn-unsel-prd) also on this row. So click on this should be avoid here.
        // Allowed only the clicks on <td>
        if (event.target.tagName.toLowerCase() === 'td') {
            $(this).find('.btn-unsel-prd').focus()
        }
    });

    $(document).on('mousedown', '#tbl_prd .btn-unsel-prd', function () {
        var prd_id = $(this).closest('tr').find('.prd_id').val();
        var prd_name = $(this).closest('tr').find('.prd_name').val();

        // #product_load_form @ price_groups_products\product_not_added.php
        var pgp_id = $('form#product_load_form .pgp_option').val();
        if (!pgp_id) {
            Swal.fire('Oops!', "Please select a price group", 'error');
            return true;
        }

        var pgp_name = $('form#product_load_form .pgp_option option:selected').text();
        load_prd_details(prd_id, prd_name, pgp_id, pgp_name);
    });


    $(document).on('click', '#product-details #tbl_prd_dt .rem', function () {
        $(this).closest('tr').remove();
    })



    $('form#product_load_form').on('submit', function (e) {
        e.preventDefault();
        loadProducts(0)
    });



    // .pgprd-index         @   price_groups_products\index.php
    // .prd-unselected      @   price_groups_products\add.php
    // #tbl_prd             @   price_groups_products\product_not_added.php
    $(document).on('keyup', '.pgprd-index .prd-unselected #tbl_prd', function (e) {

        var container = $('.pgprd-index .prd-unselected #tbl_prd');

        var key = e.keyCode || e.which;
        var downArrow = 40;
        var upArrow = 38;

        var btn = container.find('.btn-unsel-prd');
        var cur = container.find('.btn-unsel-prd:focus');

        if (!btn.length)
            return;

        // Index of focused btn
        var i = btn.index(cur);

        // If DOWN ARROW key pressed
        if (key == downArrow) {

            e.preventDefault();

            if (btn.eq(i + 1).length) {
                btn.eq(i + 1).focus();
            }
        }

        // If UP ARROW key pressed
        else if (key == upArrow) {
            e.preventDefault();

            if (btn.eq(i - 1).length) {
                btn.eq(i - 1).focus();
            }
        }

        else if (key == ENTER) {
            e.preventDefault();
            // #product_load_form @ price_groups_products\product_not_added.php
            var pgp_id = $('form#product_load_form .pgp_option').val();
            if (!pgp_id) {
                Swal.fire('Oops!', "Please select a price group", 'error');
                return true;
            }

            var pgp_name = $('form#product_load_form .pgp_option option:selected').text();
            var prd_id = cur.closest('tr').find('.prd_id').val();
            var prd_name = cur.closest('tr').find('.prd_name').val();
            load_prd_details(prd_id, prd_name, pgp_id, pgp_name);
        }
    });

    // #product-details: Modal @ price_groups_products\product_details.php
    $(document).on('keyup', '#product-details #tbl_prd_dt .pgprd_dsc', function (e) {
        var row = $(this).closest('tr');
        row.find('.pgprd_dscp, .pgprd_rate').val('');
        calculate(row, $(this).val(), '', '');
    });

    // #product-details: Modal @ price_groups_products\product_details.php
    $(document).on('keyup', '#product-details #tbl_prd_dt .pgprd_dscp', function (e) {
        var row = $(this).closest('tr');
        row.find('.pgprd_dsc, .pgprd_rate').val('');
        calculate(row, '', $(this).val(), '');
    });

    // #product-details: Modal @ price_groups_products\product_details.php
    $(document).on('keyup', '#product-details #tbl_prd_dt .pgprd_rate', function (e) {
        var row = $(this).closest('tr');
        row.find('.pgprd_dsc , .pgprd_dscp').val('');
        calculate(row, '', '', $(this).val());
    });


}); // $(document).ready();

function getAddedHTMLMark() {
    return '<i class="yes fal fa-check text-success cursor-pointer" title="Added to Price Group" style="font-size: 30px;"></i>';
}

function getNotAddedHTMLMark() {
    return '<i class="no fal fa-times text-danger cursor-pointer" title="Not Added to Price Group" style="font-size: 30px;"></i>';
}

function calculate(row, pgprd_dsc, pgprd_dscp, pgprd_rate) {
    var mrp = row.find('.mrp').text();

    if (!mrp) return;

    if (pgprd_dsc) {
        row.find('.pgprd_dscp').val(parseFloat(bcmul(bcdiv(pgprd_dsc, mrp, 5), 100, 5)));
        row.find('.pgprd_rate').val(parseFloat(bcsub(mrp, pgprd_dsc, 5)));
    }
    else if (pgprd_dscp) {
        var d = bcmul(mrp, bcdiv(pgprd_dscp, 100, 5), 5); // Discount
        row.find('.pgprd_dsc').val(parseFloat(d));
        row.find('.pgprd_rate').val(parseFloat(bcsub(mrp, d, 5)));
    }
    else if (pgprd_rate) {
        var d = bcsub(mrp, pgprd_rate, 5); // Discount
        row.find('.pgprd_dsc').val(parseFloat(d));
        row.find('.pgprd_dscp').val(parseFloat(bcmul(bcdiv(d, mrp, 5), 100, 5)));
    }
}

function onProductPerpageChanged() {
    loadProducts(0)
}

// Load pagination
function loadProducts(pagno) {

    var pgp_id = $('#product_load_form .pgp_option').val(); // @ price_groups_products\product_not_added.php

    if (!pgp_id) {
        Swal.fire('Oops!', "Please select a price group", 'error');
        return true;
    }

    var input = $('form#product_load_form').serializeArray();

    // adding an additional input
    input.push({ name: "offset", value: pagno })
    input.push({ name: "per_page", value: $('.dv-perpage #prd_per_page').val() })

    var url = site_url("price_groups_products/get_unselected_products/") + pagno;
    $('form#product_load_form').postForm(url, input, afterProductLoad, '', $('#tbl_prd_container'), false)
}

function afterProductLoad(res, container, input) {
    $("#prd_pagination").html(res.page_link);
    $("#prd_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
    createProductTable(res);
    //Scroll to table if 'List Tab' is opened
    // if ($('#pgprd-add-tab').hasClass('active'))
    //     goToTbl($("#prd_pagination"), $('#tbl_prd'));
}

// Create table list
function createProductTable(res) {
    var tbl = $("#tbl_prd");
    tbl.find("tbody").empty();
    sno = Number(res.offset);
    var str = "";
    if (!res.product_data.length) {
        str = get_no_result_row(tbl);
    }

    $.each(res.product_data, function (i, row) {
        sno += 1;

        str += "<tr class='quick-search-row'>";

        // $('.export').text() will be used for export (Print/PDF/Excel)
        str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
        str += "<td class=''>";
        str += "<input type='hidden' class='prd_id' value='" + row.prd_id + "'>"
        str += "<input type='hidden' class='prd_name' value='" + row.prd_name + "'><button type='button' class='btn-unsel-prd code-block'>" + row.prd_name + '</button>';

        str += "<td class='add-status'>";
        if (row.added == 1)
            str += getAddedHTMLMark();
        else
            str += getNotAddedHTMLMark();
        str += "</td>";

        str += "</tr>";
    });
    tbl.find("tbody").append(str);
}

function load_prd_details(prd_id, prd_name, pgp_id, pgp_name) {

    if (!prd_id || !pgp_id)
        return;

    // Initializing the form with default values.
    $('#product-details #prd-dt-from').initForm();

    $('#product-details .pgprd_fk_price_groups').val(pgp_id);
    $('#product-details .pgprd_fk_products').val(prd_id);

    var tbl = $('#product-details #tbl_prd_dt');
    tbl.find('tbody').html('');

    stock = ''; // Global Variable to keep stock related values of a product

    tbl.postForm(site_url('products/get_product_data'), { prd_id: prd_id, prd_name: prd_name, pgp_name: pgp_name }, function (res) {

        if (!res.product_batch_data.length) {
            $("#tbl_prd .prd_id[value=" + res.prd_id + "]").closest('tr').next().find('.btn-unsel-prd').focus();

            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Oops!',
                icon: 'fas fa-exclamation-triangle fa-lg',
                subtitle: '',
                body: 'No batches/Stock found for the product: ' + res.prd_name
            })
            // Swal.fire({
            //     title: "Oops!",
            //     text: "No batches found for this product",
            //     type: "error",
            //     timer: 1500
            // });

            return true;
        }

        // Modal @ price_groups_products\product_details.php
        $('#product-details').modal('show');
        $('#product-details .modal-title').html('ADD ' + res.prd_name + ' TO ' + res.pgp_name);

        stock = res.avg_stock;

        var str = "";
        var stockFound;
        $.each(res.product_batch_data, function (i, row) {
            str += "<tr class='sr-movement-row'>";
            str += "<td>";
            str += '<i class="fal fa-times-circle rem cursor-pointer" title="Delete [Alt + x]"></i> ';
            str += '<span class="sr-movement-slno"></span>';
            str += "<input type='hidden' name='pgprd_fk_product_batches[]' class='pdbch_id pgprd_fk_product_batches' value='" + row.pdbch_id + "'>"
            str += "<input type='hidden' class='pdbch_name' value='" + row.pdbch_name + "'>";
            str += "</td>";
            str += "<td>";
            str += row.pdbch_name
            str += '&nbsp; <span class="cstr-stk-dt btn btn-sm btn-success btn-flat">SHOW STOCK</span>';

            // Keeping Centralstore Stock data
            str += '<div class="cstr-stk-container" style="display:none;">';
            $.each(res.cstr_stock[row.pdbch_id], function (cstr_mbr_id, cstrstk) {
                stockFound = '';
                $.each(cstrstk.stock, function (ugp_id, stock) {
                    if (parseFloat(stock.bal_qty)) {
                        stockFound += '<div class="d-inline mx-2 btn-group btn-group-sm p-0 ugp-stk">'
                        stockFound += '<input type="hidden" class="ugp_id" value="' + ugp_id + '">';
                        stockFound += '<span class="btn btn-sm btn-success btn-flat">' + parseFloat(roundNum(stock.bal_qty)) + ' ' + stock.unt_name + '</span>';
                        stockFound += '<span class="btn btn-sm btn-warning btn-flat">' + parseFloat(roundNum(stock.bal_rate, 2)) + '/' + stock.unt_name + '</span>';
                        stockFound += '<span class="btn btn-sm btn-info btn-flat show-stock-flow">STOCK FLOW</span>';
                        stockFound += '</div>'
                    }
                });
                if (stockFound) {
                    str += '    <div class="cstr-stk-row row">';
                    str += '        <div class="cstr-stk-col col col-12">';
                    str += '            <input type="hidden" class="cstr_id" value="' + cstrstk.cstr.cstr_id + '">';
                    str += '            <input type="hidden" class="cstr_mbr_id" value="' + cstr_mbr_id + '">';
                    str += '            <input type="hidden" class="prd_id" value="' + prd_id + '">';
                    str += '            <input type="hidden" class="prd_name" value="' + prd_name + '">';
                    str += '            <input type="hidden" class="pdbch_id" value="' + row.pdbch_id + '">';
                    str += '            <input type="hidden" class="pdbch_name" value="' + row.pdbch_name + '">';
                    str += cstrstk.cstr.cstr_name + ' &nbsp; ' + stockFound;
                    str += "        </div>";
                    str += '        <div class="cstr-stk-col col col-12"></div>';
                    str += "    </div>";
                }
            });
            str += "</div>";


            str += "</td>";
            str += '<td><span class="mrp">' + parseFloat(row.pdbch_mrp) + '</span></td>';
            str += "<td><input type='text' name='pgprd_qty[]' class='pgprd_qty' value=''></td>";
            str += "<td><select name='pgprd_fk_unit_groups[]' class='pgprd_fk_unit_groups'>" + res.unit_option + "</select></td>";
            str += "<td><span class='avg-rate'></span></td>";
            str += "<td><input type='text' name='pgprd_dsc[]' class='pgprd_dsc' value=''></td>";
            str += "<td><input type='text' name='pgprd_dscp[]' class='pgprd_dscp' value=''></td>";
            str += "<td><input type='text' name='pgprd_rate[]' class='pgprd_rate last-input' value=''></td>";
            str += "</tr>";
        });

        tbl.find('tbody').html(str);
        $('#product-details #tbl_prd_dt .pgprd_qty').eq(0).focus();

        // Getting avg purchase rate
        tbl.find('.pgprd_fk_unit_groups').each(function () {
            getAVGRate($(this));
        })
    }, '', tbl, false);
}

// Modal @ price_groups_products\product_details.php
$('#product-details').on('shown.bs.modal', function () {
    $('#product-details #tbl_prd_dt .pgprd_qty').eq(0).focus();
});

// Modal @ price_groups_products\product_details.php
$('#product-details').on('hide.bs.modal', function () {

    // Getting prd_id before init form
    var prd_id = $('#product-details .pgprd_fk_products').val();

    // Initializing the form.
    $('#product-details #prd-dt-from').initForm();

    // console.log(prd_id)
    // console.log('Next: ' + $("#tbl_prd .prd_id[value=" + prd_id + "]").closest('tr').next().find('.btn-unsel-prd').text())
    if (prd_id) {
        // #tbl_prd @ price_groups_products\product_not_added.php
        $("#tbl_prd .prd_id[value=" + prd_id + "]").closest('tr').next().find('.btn-unsel-prd').focus();
    }
});


function getAVGRate(untobj) {
    untobj.closest('.sr-movement-row').find('.avg-rate').html('');

    var pdbch_id = untobj.closest('.sr-movement-row').find('.pdbch_id').val();
    var ugp_id = untobj.val();

    if (!ugp_id) return;

    // 'stock' is a Global Variable to keep stock related values of a product
    if (typeof stock[pdbch_id] != 'undefined' && typeof stock[pdbch_id][ugp_id] != 'undefined')
        untobj.closest('.sr-movement-row').find('.avg-rate').html(parseFloat(roundNum(stock[pdbch_id][ugp_id]['rate'], 2)));
}


function reset_pct(form) {
    var container = form.find('.parents');
    container.html('');
    var dropdown = form.find('.pct_option');
    loadOption('product_categories/get_options', { pct_parent: 0 }, dropdown);
}

function create_pct_parent_radios(obj, radName, label, pct_id, pct_name, not) {
    var not = ifDef(not, not, '');
    var pct_id = ifDef(pct_id, pct_id, obj.val());
    var pct_name = ifDef(pct_name, pct_name, obj.find('option:selected').text());
    var container = obj.closest('.parent-dv').find('.parents');

    label = label ? '<label class="sr-label">' + label + '</label><br>' : '';
    var i = container.html() ? '<i class="fal fa-chevron-double-right"></i>' : label;
    var radLabel = radName + '_' + pct_id;
    var radio = i + '<div class="icheck-danger d-inline m-1"><input type="radio" value="' + pct_id + '" name="' + radName + '" checked id="' + radLabel + '" class="pct_parent"><label for="' + radLabel + '">' + pct_name + '</label></div>';

    container.append(radio);
    loadOption('product_categories/get_options', { pct_parent: pct_id, not: not }, obj);
    obj.val('');
}